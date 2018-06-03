<?php
  require_once 'db_utils.php';
  require_once 'parts.php';

  /**
   * @param string
   * @return integer that represent how many years passed since date in past
   */
  function calculateYearsPassedSince($date) {
    return date_diff(date_create($date), date_create('today'))->y;
  }

  /**
   * Print tag next to user's name
   * @param associative_array
   */
  function printSexAgeTag(&$user) {
    if(!isset($user[COL_USER_BIRTHDAY]) || $user[COL_USER_BIRTHDAY] === null)
      return;
    if($user[COL_USER_SEX] === SEX_MALE)
      echo "<span class=\"gender-tag gender-male\"><i class=\"fas fa-mars\"></i> " . calculateYearsPassedSince($user[COL_USER_BIRTHDAY]) . "</span>";
    else if($user[COL_USER_SEX] === SEX_FEMALE)
      echo "<span class=\"gender-tag gender-female\"><i class=\"fas fa-venus\"></i> " . calculateYearsPassedSince($user[COL_USER_BIRTHDAY]) . "</span>";
  }

  function formatDate($date) {
    echo date("d. m. Y.", strtotime($date));
  }

  function printPost(&$activity,$current_user) {
    $dateFormat = date("d. m. Y. \u H:i", strtotime($activity[COL_POST_POSTED]));
    $db = new Database;
    $userAvatar = $current_user[COL_USER_AVATAR] !== null ? $current_user[COL_USER_AVATAR] : "img/avatar.png";
    if ($current_user[COL_USER_FIRSTNAME]!=null && $current_user[COL_USER_LASTNAME]!=null) {
      $displayName = $current_user[COL_USER_FIRSTNAME] ." ". $current_user[COL_USER_LASTNAME];
    }else{
      $displayName = $current_user[COL_USER_USERNAME];
    }
    echo "<li class=\"media\">";
    echo "<img class=\"mr-3\" src=\"$userAvatar\" alt=\"$displayName\">";
    echo "<div class=\"media-body\">";
    if($activity[COL_POST_TYPE]==1){
      $question = $db->getQuestion($activity[COL_POST_ID]);
      echo "<div class=\"question\"><span class=\"author\"><h5><a href=\"profile.php?user={$current_user[COL_USER_USERNAME]}\">{$displayName}</a> je postavio pitanje </span>
        </a>";
      echo "<a href=\"question.php?id={$question[COL_QUESTION_ID]}\"><span class=\"heading\">{$question[COL_QUESTION_HEADER]} </span></a></h5>";
      echo "<div class=\"question-content\"><small>".htmlspecialchars($question[COL_POST_CONTENT])."</small></div>";
    }else if($activity[COL_POST_TYPE]==2){
      $qID=$db->getRelationFromPost($activity[COL_POST_ID]);
      $question = $db->getQuestion($qID[COL_ANSWER_PARENT]);
      echo "<div class=\"question\"><span class=\"author\"><h5><a href=\"profile.php?user={$current_user[COL_USER_USERNAME]}\">{$displayName}</a> je ostavio odgovor na pitanje </span>
        </a>";
      echo "<a href=\"question.php?id={$question[COL_POST_ID]}\"><span class=\"heading\">{$question[COL_QUESTION_HEADER]} </span></a></h5>";
      echo "<div class=\"question-content\"><small>".htmlspecialchars($activity[COL_POST_CONTENT])."</small></div>";
    }
    echo "<span class=\"profile-activity-datespan\">Postavljeno {$dateFormat}</span></div>";
    echo "</div></li><hr>";
  }

  $db = new Database;
  $opened_user = $db->getUser($_GET["user"]);

  if(!$opened_user) {
    exit("Korisnik ne postoji!");
  }

  $user_name_identifier = !empty($opened_user[COL_USER_FIRSTNAME]) && !empty($opened_user[COL_USER_LASTNAME]) ? "{$opened_user[COL_USER_FIRSTNAME]} {$opened_user[COL_USER_LASTNAME]}" : $opened_user[COL_USER_USERNAME];

  $is_opened_users_profile = $user !== null && $user[COL_USER_USERNAME] === $opened_user[COL_USER_USERNAME];
  $activities = $db->getPosts($user[COL_USER_ID]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php printIncludes($user_name_identifier) ?>
</head>
<body>
  <?php includeNavigation() ?>

  <div class="container main-container">
     <div class="row">
        <!-- USER'S INFO -->
        <div class="col-lg-3 col-md-4 cs-center">
           <div id="profile-picture" data-img="<?php echo $opened_user[COL_USER_AVATAR] !== null ? "{$opened_user[COL_USER_AVATAR]}" : "img/avatar.png" ?>"></div>
           <div class="profile-userinfo">
              <span class="profile-fullname"><?php echo "{$user_name_identifier} "; printSexAgeTag($opened_user); ?></span>
              <ul>
                 <li class="section-header">Osnovni podaci</li>
                 <li class="section-item" title="Korisničko ime"><i class="far fa-user"></i> <?php echo $opened_user[COL_USER_USERNAME] ?></li>
                 <?php if(!empty($opened_user[COL_USER_MAJOR])) { ?>
                   <li class="section-item" title="Smer"><i class="fas fa-university"></i> <?php echo $opened_user[COL_USER_MAJOR] ?></li>
                 <?php } ?>
                 <?php if(!empty($opened_user[COL_USER_ENROLLED])) { ?>
                 <li class="section-item" title="Godina upisa"><i class="far fa-calendar"></i> <?php echo $opened_user[COL_USER_ENROLLED] ?></li>
                 <?php } ?>
              </ul>
           </div>
        </div>
        <!-- CONTENT PAGE -->
        <div class="col-lg-9 col-md-8">
           <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                 <a class="nav-item nav-link active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="true">Podaci o korisniku</a>
                 <a class="nav-item nav-link" id="nav-activities-tab" data-toggle="tab" href="#nav-activities" role="tab" aria-controls="nav-activities" aria-selected="false">Aktivnost</a>
                 <?php if($is_opened_users_profile) { ?><a class="nav-item nav-link" id="nav-change-profile-tab" data-toggle="tab" href="#nav-change-profile" role="tab" aria-controls="nav-change-profile" aria-selected="false">Uredi profil</a><?php } ?>
              </div>
           </nav>
           <!-- USER'S BIO TAB -->
           <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                  <?php
                    if ($user[COL_USER_RANK] == RANK_ADMIN) {
                  ?>
                  <div class="form-result-box" id="msg-banUser"></div>
                  <?php
                    }
                  ?>
                 <h3>Podaci o korisniku</h3>
                 <div class="table-view">
                    <?php if(!empty($opened_user[COL_USER_FIRSTNAME]) && !empty($opened_user[COL_USER_LASTNAME])) { ?>
                      <div class="row">
                         <div class="col-sm-4">Ime i prezime</div>
                         <div class="col-sm-8"><?php echo "{$opened_user[COL_USER_FIRSTNAME]} {$opened_user[COL_USER_LASTNAME]}" ?></div>
                      </div>
                    <?php }
                      if(!empty($opened_user[COL_USER_EMAIL])) { ?>
                      <div class="row">
                         <div class="col-sm-4">E-mail</div>
                         <div class="col-sm-8"><?php echo $opened_user[COL_USER_EMAIL] ?></div>
                      </div>
                    <?php }
                      if(!empty($opened_user[COL_USER_SEX]) && ($opened_user[COL_USER_SEX] === SEX_MALE || $opened_user[COL_USER_SEX] === SEX_FEMALE)) { ?>
                      <div class="row">
                         <div class="col-sm-4">Pol</div>
                         <div class="col-sm-8"><?php echo $opened_user[COL_USER_SEX] === SEX_MALE ? "Muško" : "Žensko" ?></div>
                      </div>
                    <?php }
                      if(!empty($opened_user[COL_USER_BIRTHDAY])) { ?>
                      <div class="row">
                         <div class="col-sm-4">Datum rođenja</div>
                         <div class="col-sm-8"><?php formatDate($opened_user[COL_USER_BIRTHDAY]) ?></div>
                      </div>
                    <?php }
                      if(!empty($opened_user[COL_USER_REGISTERED])) { ?>
                      <div class="row">
                         <div class="col-sm-4">Datum registracije</div>
                         <div class="col-sm-8"><?php formatDate($opened_user[COL_USER_REGISTERED]) ?></div>
                      </div>
                    <?php }
                    if(!empty($opened_user[COL_USER_ABOUT])) { ?>
                      <div class="row">
                         <div class="col-sm-4">Biografija</div>
                         <div class="col-sm-8">
                            <?php echo $opened_user[COL_USER_ABOUT] ?>
                         </div>
                      </div>
					 <?php }
                    if(empty($opened_user[COL_REACTION_TYPE])) {?>
                      <div class="row">
                        <div class="col-sm-4">Score</div>
                        <div class="col-sm-8">
                         <?php
                            $user_id = $db->getUserID($user[COL_USER_USERNAME]);
                            $like=$db->getUserLike($user_id);
                            $dislike=$db->getUserDislike($user_id);
                            echo "like $like dislike $dislike"; 
                            ?>
                        </div>
                      </div>
                    <?php }
                      if(empty($opened_user[COL_POST_ID])) {?>
                      <div class="row">
                        <div class="col-sm-4">Broj postavljeniih pitanja  </div>
                        <div class="col-sm-8">
                         <?php
                            $user_id = $db->getUserID($user[COL_USER_USERNAME]);
                            $count=$db->getCountQuestion($user_id);
                            echo $count;
                            ?>
                        </div>
                      </div>
					<?php }
                      if(empty($opened_user[COL_ANSWER_ID])) {?>
                      <div class="row">
                        <div class="col-sm-4">Broj odgovora  </div>
                        <div class="col-sm-8">
                         <?php
                            $user_id = $db->getUserID($user[COL_USER_USERNAME]);
                            $count=$db->getCountAnswer($user_id);
                            echo $count;
                            ?>
                        </div>
                      </div>  					  
                    <?php } ?>
                 </div>
                 <?php if($is_opened_users_profile) { ?><button type="button" class="btn btn-primary" id="change-profile-button">Uredi profil</button><?php } ?>
                  <?php
                    if ($user[COL_USER_RANK] == RANK_ADMIN) {
                  ?>
                  <div class="aligned-right">
                    <form method="post" id="banUser">
                      <input type="hidden" name="formType" value="<?php echo $opened_user[COL_USER_RANK] == RANK_BANNED ? "unbanUser" : "banUser";?>">
                      <input type="hidden" name="user" value="<?php echo htmlspecialchars($_GET["user"])?>">
                      <input type="submit" value="<?php echo $opened_user[COL_USER_RANK] == RANK_BANNED ? "Odbanuj korisnika" : "Banuj korisnika" ;?>" name="banUser" class="btn btn-primary">
                    </form>
                  </div>
                  <?php
                    }
                  ?>
              </div>
              <!-- USER'S ACTIVITIES TAB -->
              <div class="tab-pane fade" id="nav-activities" role="tabpanel" aria-labelledby="nav-activities-tab">
                 <ul class="list-unstyled user-activity-list">
                          <?php
                           foreach ((array)$activities as $activity) {
                                printPost($activity, $opened_user);
                           }   
                          ?>
                 </ul>
              </div>
              <?php if($is_opened_users_profile) { ?>
              <!-- CHANGE PROFILE TAB -->
              <div class="tab-pane fade" id="nav-change-profile" role="tabpanel" aria-labelledby="nav-change-profile-tab">
                 <h3>Uredi profil</h3>
                 <form method="post">
                    <input type="hidden" name="formType" value="biography">
                    <div class="form-result-box"></div>
                    <div class="table-view">
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-firstname">Ime</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="text" class="form-control" name="firstName" id="change-profile-firstname" value="<?php echo $opened_user[COL_USER_FIRSTNAME] ?>">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-lastname">Prezime</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="text" class="form-control" name="lastName" id="change-profile-lastname" value="<?php echo $opened_user[COL_USER_LASTNAME] ?>">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-major">Smer</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="text" class="form-control" name="major" id="change-profile-major" value="<?php echo $opened_user[COL_USER_MAJOR] ?>">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-enrollmentyear">Godina upisa</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="number" class="form-control" name="enrollmentYear" id="change-profile-enrollmentyear" value="<?php echo $opened_user[COL_USER_ENROLLED] ?>">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-email">Email</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="email" class="form-control" name="email" id="change-profile-email" value="<?php echo $opened_user[COL_USER_EMAIL] ?>">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-sex">Pol</label>
                          </div>
                          <div class="col-sm-8">
                             <select name="sex" id="change-profile-sex" class="form-control">
                                <option value="m"<?php if($opened_user[COL_USER_SEX] === SEX_MALE) echo " selected" ?>>Muško</option>
                                <option value="f"<?php if($opened_user[COL_USER_SEX] === SEX_FEMALE) echo " selected" ?>>Žensko</option>
                                <option value="none">Ne želim da se izjasnim</option>
                             </select>
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-dateofbirth">Datum rođenja</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="date" class="form-control" name="dateOfBirth" id="change-profile-dateofbirth" <?php if($opened_user[COL_USER_BIRTHDAY] !== null) echo "value=\"{$opened_user[COL_USER_BIRTHDAY]}\""?>>
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-bio">Biografija</label>
                          </div>
                          <div class="col-sm-8">
                             <textarea name="biography" id="change-profile-bio" rows="20" class="form-control"><?php echo $opened_user[COL_USER_ABOUT] ?></textarea>
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-authenticatepasswordabout">Šifra</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="password" name="authenticationPassword" id="change-profile-authenticatepasswordabout" class="form-control">
                             <small class="form-text text-muted">Da bi sačuvao promene potrebno je da uneseš trenutnu lozinku radi autentifikacije</small>
                          </div>
                       </div>
                    </div>
                    <button style="margin-top:20px;font-size:13px" id="delete-user-opener" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#delete-user-dialog">Izbriši nalog</button>
                    <button id="change-password-opener" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#change-password-dialog">Promeni lozinku</button>
                    <!--CSS move --> 
                    <button style="margin-top:20px;font-size:13px" id="change-avatar-opener" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#change-avatar-dialog">Promeni avatar</button>
                    <input type="submit" name="saveProfileChanges" value="Sačuvaj izmene" class="btn btn-primary">
                 </form>
                 <!-- DELETE USER DIALOG -->
                 <div id="delete-user-dialog" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                       <div class="modal-content">
                          <div class="modal-header">
                             <h5 class="modal-title">Brisanje naloga</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                             </button>
                          </div>
                          <form method="post">
                             <input type="hidden" name="formType" value="deleteUser">
                             <div class="modal-body">
                                <div class="form-result-box"></div>
                                <p>Za brisanje naloga morate dva puta unesiti vašu lozinku.</p>
                                <div class="form-group">
                                   <label for="password">Lozinka:</label>
                                   <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <div class="form-group">
                                   <label for="password-repeated">Lozinka ponovo:</label>
                                   <input type="password" name="password-repeated" class="form-control" id="password-repeated">
                                </div>
                             </div>
                             <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Izađi</button>
                                <input type="submit" name="delete-user" class="btn btn-primary" value="Sačuvaj izmene">
                             </div>
                          </form>
                       </div>
                    </div>
                  </div>
                 <!-- CHANGE PASSWORD DIALOG -->
                 <div id="change-password-dialog" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                       <div class="modal-content">
                          <div class="modal-header">
                             <h5 class="modal-title">Izmeni lozinku</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                             </button>
                          </div>
                          <form method="post">
                             <input type="hidden" name="formType" value="password">
                             <div class="modal-body">
                                <div class="form-result-box"></div>
                                <div class="form-group">
                                   <label for="current-password">Lozinka:</label>
                                   <input type="password" name="current-password" class="form-control" id="current-password">
                                </div>
                                <div class="form-group">
                                   <label for="new-password">Nova lozinka:</label>
                                   <input type="password" name="new-password" class="form-control" id="new-password">
                                </div>
                                <div class="form-group">
                                   <label for="new-password-repeated">Ponovo unesi novu lozinku:</label>
                                   <input type="password" name="new-password-repeated" class="form-control" id="new-password-repeated">
                                </div>
                             </div>
                             <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Izađi</button>
                                <input type="submit" name="savePasswordChanges" class="btn btn-primary" value="Sačuvaj izmene">
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>
                 <!-- CHANGE AVATAR DIALOG -->
                 <div id="change-avatar-dialog" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                       <div class="modal-content">
                          <div class="modal-header">
                             <h5 class="modal-title">Izmeni avatar</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                             </button>
                          </div>
                          <form action="formHandler.php" method="post" enctype="multipart/form-data">
                             <input type="hidden" name="formType" value="avatar">
                             <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                             <div class="modal-body">
                                <div class="form-result-box"></div> 
                                <div class="form-group">
                                   <label for="new-avatar">Vaša slika za avatar:</label>
                                   <input type="file" name="photo" id="photo" class="form-control">
                                </div>
                             </div>
                             <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Izađi</button>
                                <input type="submit" name="saveAvatarChanges" class="btn btn-primary" value="Sačuvaj izmene">
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>
              </div>
              <?php } ?>
           </div>
        </div>
     </div>
     <?php includeFooter() ?>
  </div>

  <?php includeScripts() ?>
  <script>
    $(document).ready(function(){
        $('html').animate({scrollTop:0}, 500);
        $('body').animate({scrollTop:0}, 500);
    });

    var profilePic = document.getElementById("profile-picture");
    profilePic.style["background-image"] = "url(\"" + profilePic.dataset.img + "\")";
    <?php if($is_opened_users_profile) { ?>

      // THE FOLLOWING CODE SHOULD BE SHOWN ONLY IF USER LOOKS HIS PROFILE
      var msgCookie = $.cookie("biography-user-message");
      if(msgCookie !== undefined && msgCookie !== "") {
        $("#nav-change-profile .form-result-box:first").html(msgCookie);
        $("#nav-about-tab").removeClass("active");
        $("#nav-change-profile-tab").addClass("active");
        $("#nav-about").removeClass("show active");
        $("#nav-change-profile").addClass("show active");
        $.removeCookie("biography-user-message");
      }

      var userID = <?php echo $opened_user[COL_USER_ID] ?>; // PUT ACTUAL USER ID VIA PHP

      $("#change-profile-button").click(function() {
        $('#nav-tab a[href="#nav-change-profile"]').tab('show');
      });
    <?php }?>
      $("form[method=\"post\"]").submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var messageBox = form.find(".form-result-box");
        if (form.attr("id") === "banUser") {
           messageBox = $("#msg-banUser");
        }
        var data = new FormData(this);
        var output;
        $.ajax({
          url: 'formHandler.php',
          type: 'post',
          method: 'post',
          dataType: 'json',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          success: function(result) {
            try {
              if(result.errors.length === 0) {
                output = "<div class=\"alert alert-success\" role=\"alert\">Izmene su uspešno sačuvane!</div>";

                // CLEAN PASSWORD FIELDS DUE TO SECURITY REASONS
                form.find("input[type=\"password\"]").val("");

                // SWITCH MAKES IT EASY TO ADD MORE FUNCTIONALITY
                switch(form.find("input[name=\"formType\"]").val()) {
                  case "biography":
                    $.cookie("biography-user-message", output);
                    location.reload();
                    break;
                  case "avatar":
                    location.reload();
                    break;
                  case "banUser":
                    location.reload();
                    break;
                  case "unbanUser":
                    location.reload();
                    break;
                }
              }
              else
                output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.errors.join("<br>") + "</div>";
            }
            catch(err) {
              output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa serverom, molimo pokušajte kasnije!</div>";
            }
          },
          error: function() {
            output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa serverom, molimo pokušajte kasnije!</div>";
          },
          complete: function() {
            messageBox.html(output);
          }
        });
      });
  </script>
</body>
</html>
