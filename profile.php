<?php
  require_once 'database/db_utils_dev.php';
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php printIncludes('Pera Perić') ?>
</head>
<body>
  <?php includeNavigation() ?>

  <div class="container main-container">
     <div class="row">
        <!-- USER'S INFO -->
        <div class="col-lg-3 col-md-4 cs-center">
           <div id="profile-picture" data-img="img/profile-pic.jpg"></div>
           <div class="profile-userinfo">
              <span class="profile-fullname">Pera Perić <?php printSexAgeTag($a=array(COL_USER_SEX=>SEX_MALE, COL_USER_BIRTHDAY=>"1997-01-15")); ?></span>
              <ul>
                 <li class="section-header">Osnovni podaci</li>
                 <li class="section-item" title="Korisničko ime"><i class="far fa-user"></i> peraPeric</li>
                 <li class="section-item" title="Smer"><i class="fas fa-university"></i> Računarske nauke</li>
                 <li class="section-item" title="Godina upisa"><i class="far fa-calendar"></i> 2017</li>
              </ul>
           </div>
        </div>
        <!-- CONTENT PAGE -->
        <div class="col-lg-9 col-md-8">
           <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                 <a class="nav-item nav-link active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="true">Podaci o korisniku</a>
                 <a class="nav-item nav-link" id="nav-activities-tab" data-toggle="tab" href="#nav-activities" role="tab" aria-controls="nav-activities" aria-selected="false">Aktivnost</a>
                 <a class="nav-item nav-link" id="nav-change-profile-tab" data-toggle="tab" href="#nav-change-profile" role="tab" aria-controls="nav-change-profile" aria-selected="false">Uredi profil</a>
              </div>
           </nav>
           <!-- USER'S BIO TAB -->
           <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                 <h3>Podaci o korisniku</h3>
                 <div class="table-view">
                    <div class="row">
                       <div class="col-sm-4">Ime i prezime</div>
                       <div class="col-sm-8">Pera Perić</div>
                    </div>
                    <div class="row">
                       <div class="col-sm-4">E-mail</div>
                       <div class="col-sm-8">pera.peric@gmail.com</div>
                    </div>
                    <div class="row">
                       <div class="col-sm-4">Pol</div>
                       <div class="col-sm-8">Muško</div>
                    </div>
                    <div class="row">
                       <div class="col-sm-4">Datum rođenja</div>
                       <div class="col-sm-8">15. 01. 1997.</div>
                    </div>
                    <div class="row">
                       <div class="col-sm-4">Datum registracije</div>
                       <div class="col-sm-8">03. 01. 2018.</div>
                    </div>
                    <div class="row">
                       <div class="col-sm-4">Biografija</div>
                       <div class="col-sm-8">
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque tristique nisl nunc, ut vulputate purus porttitor et. Duis porttitor vitae ipsum eget convallis. Pellentesque feugiat rhoncus enim in rutrum. Pellentesque aliquet mi nisi, ac lacinia odio egestas tincidunt. In non feugiat mauris, et bibendum neque. Integer accumsan dolor quis ornare hendrerit. Donec elementum ante sit amet rutrum cursus. Aenean vehicula lacus at leo lobortis commodo. Fusce suscipit metus velit, in facilisis massa semper eget. Duis venenatis massa massa, molestie dictum tellus feugiat vel. Mauris et ipsum faucibus, vehicula erat et, vulputate lorem. Nulla tempus convallis metus ut tincidunt. Etiam fringilla aliquet ante id porta. Proin viverra massa quis orci sagittis, ut tincidunt nulla consectetur.</p>
                          <p>Vestibulum pretium ante eu mauris pellentesque mattis. Cras eget aliquet ligula. Vestibulum a blandit nisi. Donec sed ultricies nisi, ut consectetur justo. Vivamus viverra erat leo, sit amet viverra lectus malesuada sed. Fusce auctor iaculis metus ac mollis. Duis ornare cursus volutpat. Nulla non aliquam sapien. Pellentesque euismod et lorem eu varius. Nam feugiat est cursus, venenatis massa sit amet, sodales metus. Nam odio felis, viverra et lectus sit amet, tincidunt dapibus mauris.</p>
                          <p>Quisque ultricies est nec metus feugiat pulvinar. Nulla ac dui non sem maximus consequat sit amet ut justo. Sed egestas rutrum ipsum, et maximus lacus pretium vel. Maecenas facilisis aliquam tincidunt. Aenean semper fermentum diam, at gravida tellus tincidunt ut. Integer blandit fermentum tincidunt. Curabitur vel tortor massa. Fusce fringilla justo at viverra accumsan. Fusce nec sapien ligula. Aenean vitae urna mi.</p>
                          <p>Duis ligula magna, suscipit at eros eu, fringilla molestie eros. Curabitur pellentesque neque eu erat porta rutrum. Pellentesque nunc dui, ultricies sit amet viverra eget, elementum eu tellus. Nulla pulvinar nulla tempor, sodales massa id, porttitor augue. Duis imperdiet dapibus fermentum. Nam fringilla at lorem non commodo. Phasellus tincidunt feugiat dignissim. Duis tincidunt lacus vitae velit malesuada molestie. Pellentesque purus eros, rhoncus non hendrerit sodales, sagittis ac augue. Aliquam molestie sapien vel ante ultrices, nec ultrices erat convallis. Quisque nibh lorem, commodo vehicula tincidunt nec, mollis eget risus. Vivamus eu aliquam sapien.</p>
                          <p>Fusce suscipit eros eget arcu dignissim ultrices. Sed vitae lobortis velit. Nam rutrum ornare justo id tincidunt. Nulla vel maximus mauris. Nam non auctor lorem. Fusce faucibus a odio et congue. Donec tempor fringilla nunc ut ornare. Phasellus pretium nulla id placerat varius. Nam nec fringilla nibh, quis rutrum nulla. Aliquam rutrum sodales mauris. Morbi ullamcorper justo egestas molestie dignissim. Quisque sit amet sollicitudin ligula, et semper metus. Etiam non augue sem. Praesent fringilla dignissim dolor eu pellentesque.</p>
                       </div>
                    </div>
                 </div>
                 <button type="button" class="btn btn-primary" id="change-profile-button">Uredi profil</button>
              </div>
              <!-- USER'S ACTIVITIES TAB -->
              <div class="tab-pane fade" id="nav-activities" role="tabpanel" aria-labelledby="nav-activities-tab">
                 <ul class="list-unstyled user-activity-list">
                    <li class="media">
                       <img class="mr-3" src="img/profile-pic.jpg" alt="Pera Peric">
                       <div class="media-body">
                          <h5><a href="#">Pera Perić</a> je postavio pitanje <a href="#">Customize Android app based on server values</a></h5>
                          <small>I want to developer an Android app such that a particular function should be executed in the app only when a value in the server is true. If the value in the server is false another function should be executed...</small>
                          <span class="profile-activity-datespan">Postavljeno 03. 01. 2018. u 16:67</span>
                       </div>
                    </li>
                    <hr>
                    <li class="media">
                       <img class="mr-3" src="img/profile-pic.jpg" alt="Pera Peric">
                       <div class="media-body">
                          <h5><a href="#">Pera Perić</a> je ostavio odgovor na pitanje <a href="#">Passing anonymous parameters to functions in C</a></h5>
                          <small>Spanner is Google's globally distributed relational database management system (RDBMS), the successor to BigTable. Google claims it is not a pure relational system because each table must have a primary key. Here is the link of the paper. Spanner is Google's scalable, multi-version, globally-distributed, and synchronously-replicated database. It is the first system to distribute data at global scale and support externally-consistent distributed transactions. This paper describes how Spanner is structured, its feature set, the rationale underlying various design decisions, and a novel time API that exposes clock uncertainty. This API and its implementation are critical to supporting external consistency and a variety of powerful features: non-blocking reads in the past, lock-free read-only transactions, and atomic schema changes, across all of Spanner.</small>
                          <span class="profile-activity-datespan">Postavljeno 03. 01. 2018. u 16:67</span>
                       </div>
                    </li>
                    <hr>
                    <li class="media">
                       <img class="mr-3" src="img/profile-pic.jpg" alt="Pera Peric">
                       <div class="media-body">
                          <h5><a href="#">Pera Perić</a> je postavio pitanje <a href="#">symfony heroku fos_user.registration failure</a></h5>
                          <small>I've recently deployed a symfony 3.4.1 application on Heroku. There are two tables in the database : Post(filled with dataFixtures --no problem) and User (using the FosUserBundle). When trying to register a user i've got this in the log:...</small>
                          <span class="profile-activity-datespan">Postavljeno 03. 01. 2018. u 16:67</span>
                       </div>
                    </li>
                    <hr>
                    <li class="media">
                       <img class="mr-3" src="img/profile-pic.jpg" alt="Pera Peric">
                       <div class="media-body">
                          <h5><a href="#">Pera Perić</a> je postavio pitanje <a href="#">symfony heroku fos_user.registration failure</a></h5>
                          <small>It should work.</small>
                          <span class="profile-activity-datespan">Postavljeno 03. 01. 2018. u 16:67</span>
                       </div>
                    </li>
                 </ul>
              </div>
              <!-- CHANGE PROFILE TAB -->
              <div class="tab-pane fade" id="nav-change-profile" role="tabpanel" aria-labelledby="nav-change-profile-tab">
                 <h3>Uredi profil</h3>
                 <form>
                    <input type="hidden" name="formType" value="biography">
                    <div class="form-result-box"></div>
                    <div class="table-view">
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-firstname">Ime</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="text" class="form-control" name="firstName" id="change-profile-firstname" value="Pera">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-lastname">Prezime</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="text" class="form-control" name="lastName" id="change-profile-lastname" value="Perić">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-major">Smer</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="text" class="form-control" name="major" id="change-profile-major" value="Računarske nauke">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-enrollmentyear">Godina upisa</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="number" class="form-control" name="enrollmentYear" id="change-profile-enrollmentyear" value="2017">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-email">Email</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="email" class="form-control" name="email" id="change-profile-email" value="pera.peric@gmail.com">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-sex">Pol</label>
                          </div>
                          <div class="col-sm-8">
                             <select name="sex" id="change-profile-sex" class="form-control">
                                <option value="male">Muško</option>
                                <option value="female">Žensko</option>
                                <option value="none">Ne želim da se izjasnim</option>
                             </select>
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-dateofbirth">Datum rođenja</label>
                          </div>
                          <div class="col-sm-8">
                             <input type="date" class="form-control" name="dateOfBirth" id="change-profile-dateofbirth" value="1997-01-15">
                          </div>
                       </div>
                       <div class="row">
                          <div class="col-sm-4">
                             <label for="change-profile-bio">Biografija</label>
                          </div>
                          <div class="col-sm-8">
                             <textarea name="biography" id="change-profile-bio" rows="20" class="form-control">
                             Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque tristique nisl nunc, ut vulputate purus porttitor et. Duis porttitor vitae ipsum eget convallis. Pellentesque feugiat rhoncus enim in rutrum. Pellentesque aliquet mi nisi, ac lacinia odio egestas tincidunt. In non feugiat mauris, et bibendum neque. Integer accumsan dolor quis ornare hendrerit. Donec elementum ante sit amet rutrum cursus. Aenean vehicula lacus at leo lobortis commodo. Fusce suscipit metus velit, in facilisis massa semper eget. Duis venenatis massa massa, molestie dictum tellus feugiat vel. Mauris et ipsum faucibus, vehicula erat et, vulputate lorem. Nulla tempus convallis metus ut tincidunt. Etiam fringilla aliquet ante id porta. Proin viverra massa quis orci sagittis, ut tincidunt nulla consectetur.
                             Vestibulum pretium ante eu mauris pellentesque mattis. Cras eget aliquet ligula. Vestibulum a blandit nisi. Donec sed ultricies nisi, ut consectetur justo. Vivamus viverra erat leo, sit amet viverra lectus malesuada sed. Fusce auctor iaculis metus ac mollis. Duis ornare cursus volutpat. Nulla non aliquam sapien. Pellentesque euismod et lorem eu varius. Nam feugiat est cursus, venenatis massa sit amet, sodales metus. Nam odio felis, viverra et lectus sit amet, tincidunt dapibus mauris.
                             Quisque ultricies est nec metus feugiat pulvinar. Nulla ac dui non sem maximus consequat sit amet ut justo. Sed egestas rutrum ipsum, et maximus lacus pretium vel. Maecenas facilisis aliquam tincidunt. Aenean semper fermentum diam, at gravida tellus tincidunt ut. Integer blandit fermentum tincidunt. Curabitur vel tortor massa. Fusce fringilla justo at viverra accumsan. Fusce nec sapien ligula. Aenean vitae urna mi.
                             Duis ligula magna, suscipit at eros eu, fringilla molestie eros. Curabitur pellentesque neque eu erat porta rutrum. Pellentesque nunc dui, ultricies sit amet viverra eget, elementum eu tellus. Nulla pulvinar nulla tempor, sodales massa id, porttitor augue. Duis imperdiet dapibus fermentum. Nam fringilla at lorem non commodo. Phasellus tincidunt feugiat dignissim. Duis tincidunt lacus vitae velit malesuada molestie. Pellentesque purus eros, rhoncus non hendrerit sodales, sagittis ac augue. Aliquam molestie sapien vel ante ultrices, nec ultrices erat convallis. Quisque nibh lorem, commodo vehicula tincidunt nec, mollis eget risus. Vivamus eu aliquam sapien.
                             Fusce suscipit eros eget arcu dignissim ultrices. Sed vitae lobortis velit. Nam rutrum ornare justo id tincidunt. Nulla vel maximus mauris. Nam non auctor lorem. Fusce faucibus a odio et congue. Donec tempor fringilla nunc ut ornare. Phasellus pretium nulla id placerat varius. Nam nec fringilla nibh, quis rutrum nulla. Aliquam rutrum sodales mauris. Morbi ullamcorper justo egestas molestie dignissim. Quisque sit amet sollicitudin ligula, et semper metus. Etiam non augue sem. Praesent fringilla dignissim dolor eu pellentesque.
                             </textarea>
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
                    <button id="change-password-opener" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#change-password-dialog">Promeni lozinku</button>
                    <input type="submit" name="saveProfileChanges" value="Sačuvaj izmene" class="btn btn-primary">
                 </form>
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
                          <form>
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
              </div>
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

    var userID = 0; // PUT ACTUAL USER ID VIA PHP

    $("#change-profile-button").click(function() {
      $('#nav-tab a[href="#nav-change-profile"]').tab('show');
    });

    $("form").submit(function(event) {
      event.preventDefault();
      var form = $(this);
      var messageBox = form.find(".form-result-box");
      var data = form.serialize();
      data += "&&userID=" + userID;
      var output;
      $.ajax({
        url: 'formHandler.php',
        type: 'post',
        dataType: 'json',
        data: data,
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
              }
            }
            else
              output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.errors.join("<br>") + "</div>";
          }
          catch(err) {
            output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa servevom, molimo pokušajte kasnije!</div>";
          }
        },
        error: function() {
          output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa servevom, molimo pokušajte kasnije!</div>";
        },
        complete: function() {
          messageBox.html(output);
        }
      });
    });
  </script>
</body>
</html>
