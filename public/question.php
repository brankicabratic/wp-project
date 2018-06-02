<?php 
  require_once 'parts.php';
  require_once 'db_utils.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <?php printIncludes('Pitanje') ?>
  </head>
  <body>

    <div class="container main-container">
      <?php includeNavigation() ?>

      <div class="row">
        <div class="col-lg-1"><!-- Sometime in the future something may even be here! It only exists for filling up the space at the moment. --></div>
        <div class="col-lg-8">
          <div id="the-question">
            <?php
              if ($user[COL_USER_RANK] == RANK_ADMIN) {
            ?>
            <div class="form-result-box" id="msg-deleteQuestion"></div>
            <?php
              }
            ?>
            <div class="question-header">
              <?php
                $db = new Database;
                $answers = array();
                if (isset($_GET["id"])) {
                  $questionId = $_GET["id"];
                  $question = $db->getQuestion($questionId);
                  $answers = $db->getAnswersRelatedToQuestion($questionId);
                  if (is_null($question)) {
                    header("Location: questionNotFound.php");
                    exit();
                  } else {
                    echo "<h1>$question[Header]</h1>";
                    $nameToShow = $question[COL_USER_USERNAME];
                    $firstName = $question[COL_USER_FIRSTNAME];
                    $lastName = $question[COL_USER_LASTNAME];
                    if (!empty($firstName) && !empty($lastName)) {
                      $nameToShow = "$firstName $lastName";
                    }
                    echo "<small>Pitao <a href=\"profile.php?user=".$question[COL_USER_USERNAME]."\">$nameToShow</a> ".$question[COL_POST_POSTED]."</small>";
                  }
                }
              ?>
              <div class="tags">
                <?php
                if (isset($_GET["id"])) {
                	$question = $db->getQuestion($_GET["id"]);
                	$tags = $db->getTagsRelatedToQuestion($questionId);
                  foreach($tags as $tag) {
                    echo "<a href=\"index.php?tagName={$tag[COL_TAG_NAME]}\"><div class=\"tag\">{$tag[COL_TAG_NAME]}</div></a>";
                  }
                }
                ?>
              </div>
            </div>
            <?php 
              if (isset($_GET["id"])) {
                $question = $db->getQuestion($_GET["id"]);
                echo "<div class=\"question-content\">".htmlspecialchars($question[COL_POST_CONTENT])."</div>";
              }
            ?>
            <?php
			    if($user){
					$user_id = $db->getUserID($user[COL_USER_USERNAME]);
					if(isset($_POST["decrement"])) {      
            $deleteReact=$db->deleteReaction($user_id, $_GET["id"]);     
            $reaction=$db->getPostsReaction($_GET["id"], $user_id );
							if($reaction==0 || $reaction==+1){
  							$insertReact=$db->insertReaction($_GET["id"], $user_id, -1);
							}
          }

          if(isset($_POST["increment"])) {      
            $deleteReact=$db->deleteReaction($user_id, $_GET["id"]);      
            $reaction=$db->getPostsReaction($_GET["id"], $user_id );            
            	if($reaction==0 || $reaction==-1){
                $insertReact=$db->insertReaction($_GET["id"], $user_id, +1);
               }
            }
          }
        ?>
       
          <form id="questionScore" method="post">
            <div class="question-footer">
              <span class="score">
              <input type="submit" name="decrement" value="-"/>
                <span id="demo"><?php $score=$db->getPostsScore($_GET["id"]); echo "$score"; ?></span>
                <input type="submit" name="increment" value="+"/>
                </span>
            </div>
          </div>
          </form>
              <div class="aligned-right">
                <?php
                  if ($user[COL_USER_RANK] == RANK_ADMIN) {
                ?>
                <form id="deleteQuestion" method="post">
                  <input type="hidden" name="formType" value="deleteQuestion">
                  <input type="hidden" name="id" value="<?php echo $_GET["id"]?>">
                  <input type="submit" value="Izbriši pitanje" class="btn btn-primary">
                </form>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-1 d-none d-lg-block scroll-top-answers-side">
              <span class="arrow invisible back-to-top-button"><a title="Back to top" href="#the-question"><i class="fas fa-arrow-circle-up"></i></a></span>
            </div>
            <div class="col-lg-11">
              <h4>Ostavi odgovor:</h4>
              <div class="write-answer">
                <div class="form-result-box" id="msg-postAnswer"></div>
                <form method="post" id="answer-form">
                    <input type="hidden" name="formType" value="answerQuestionForm">
                    <div class="text-formating-tools">
                      <span class="tool" onclick="tools.addCustomTag(' [superscript]', '[/superscript] ')"><i class="fas fa-superscript"></i></span>
                      <span class="tool" onclick="tools.addCustomTag('[code lang=\'html\']\n', '\n[/code]')"><i class="fas fa-code"></i></span>
                    </div>
                    <textarea name="answer-content" spellcheck="false" placeholder="Odgovor"></textarea>
                    <input type="hidden" name="questionId" value="<?php echo $questionId ?>">
                    <div class="submit-group">
                      <input type="submit" class="btn btn-primary" value="Odgovori">
                    </div>
                </form>
              </div>

              <div id="answers">
                <?php
                    for($i = 0; $i < count($answers); $i++) {
                        $nameToShow = $answers[$i][COL_USER_USERNAME];
                        $firstName = $answers[$i][COL_USER_FIRSTNAME];
                        $lastName = $answers[$i][COL_USER_LASTNAME];
                        if (!empty($firstName) && !empty($lastName)) {
                            $nameToShow = "$firstName $lastName";
                        }
                        if ($user[COL_USER_RANK] == RANK_ADMIN) {
                ?>
                <div class="form-result-box" id="<?php echo "msg-deleteAnswer-".$answers[$i][COL_POST_ID]?>"></div>
                <?php
                        }
                ?>
                <div class="answer">
                  <div class="content">
                    <?php echo htmlspecialchars($answers[$i][COL_POST_CONTENT]) ?>
                  </div>
                  <?php
                      if($user){
                        
                        if(isset($_POST["decrementAnswerScore"])) {   
                          if(isset($_POST["answerID"])){
                          $deleteReact=$db->deleteReaction($user_id, $_POST["answerID"]);						
                          $reaction=$db->getPostsReaction($_POST["answerID"], $user_id );
                          if($reaction==0 || $reaction==+1){
                              $insertReact=$db->insertReaction($_POST["answerID"], $user_id, -1); 
                          }
                        }
                       }
            
                        if(isset($_POST["incrementAnswerScore"])) {
                            if(isset($_POST["answerID"])){
                            $deleteReact=$db->deleteReaction($user_id, $_POST["answerID"]);    
						                $reaction=$db->getPostsReaction($_POST["answerID"],  $user_id);
                            if($reaction==0 || $reaction==-1){
                              $insertReact=$db->insertReaction($_POST["answerID"], $user_id, +1);
                            }
                          }
                        }
                      }
                      ?> 
                      

                  <div class="footer">
                    <div class="aligned-right">
                      <span class="score">
                       <form id="answerScore" method="post">
                        <input type="hidden" name="answerID" value="<?php echo  $answers[$i][COL_POST_ID];?>">
                        <input type="submit" name="decrementAnswerScore" value="-"/>
                        <span class="actual-score"> <?php $score=$db->getPostsScore( $answers[$i][COL_POST_ID]); echo $score; ?> </span>
                		    <input type="submit" name="incrementAnswerScore" value="+"/>
                       </form>
					  </span>
                      <?php echo "Odgovorio <a href=\"profile.php?user=".$answers[$i][COL_USER_USERNAME]."\">$nameToShow</a> ".$answers[$i][COL_POST_POSTED]; ?>
                      <?php
                        if ($user[COL_USER_RANK] == RANK_ADMIN) {
                      ?>
                      <form id="<?php echo "deleteAnswer-".$answers[$i][COL_POST_ID]?>" method="post">
                       <input type="hidden" name="formType" value="deleteAnswer">
                        <input type="hidden" name="id" value="<?php echo $answers[$i][COL_POST_ID]?>">
                        <input type="submit" value="Izbriši odgovor" class="btn btn-primary">
                      </form>
                      <?php
                        }
                       ?>
                    </div>
                  </div>
                </div>
                <?php
                    }
                ?>

              </div>
              <span class="back-to-top-button d-lg-none"><a title="Back to top" href="#the-question"><i class="fas fa-arrow-circle-up"></i></a></span>
            </div>
          </div>


        </div>
        <div class="col-lg-2"><!-- Sometime in the future something may even be here! It only exists for filling up the space at the moment. --></div>
      </div>

      <?php includeFooter() ?>
    </div>

    <?php includeScripts() ?>
    <script type="text/javascript">
      // Side scroll to top
      var arrowContainer = $(".scroll-top-answers-side");
      var arrow = arrowContainer.find(".arrow");
      var waiting = false;

      var resetWaiting = function() {
        waiting = false;
      };

      var calculateArrowToTopStyle = function() {
        if(arrowContainer.css("display") == "none" || waiting)
          return;
        waiting = true;
        setTimeout(resetWaiting, 50);
        var windowTop = $(window).scrollTop() + 60;
        var arrowHeight = arrow.height();
        var arrowContainerStart = arrowContainer.offset().top;
        var arrowContainerHeight = arrowContainer.height();
        var arrowContainerEnd = arrowContainerStart + arrowContainerHeight;

        if(arrowContainerHeight < 500 || windowTop < arrowContainerStart)
          arrow.addClass("invisible");
        else {
          var dif = windowTop - arrowContainerStart;
          arrow.css({
            "opacity": Math.min(dif / 250, 1),
            "top": Math.min(dif + 50, arrowContainerHeight - arrowHeight)
          });
          arrow.removeClass("invisible");
        }
      };

      //
      var answerInput = document.querySelector("#answer-form textarea");
      var answerInputFocused = false;
      answerInput.addEventListener("focus", function() {
        answerInputFocused = true;
      });
      answerInput.addEventListener("blur", function() {
        setTimeout(function() {
          answerInputFocused = false;
        }, 100);
      });


      var getTextAreaSelection = function() {
        if(!answerInputFocused)
          return {
            pre: answerInput.value,
            selected: '',
            post: ''
          };

        var start = answerInput.selectionStart;
        var finish = answerInput.selectionEnd;

        return {
          pre: answerInput.value.substring(0, start),
          selected: answerInput.value.substring(start, finish),
          post: answerInput.value.substring(finish, answerInput.value.length)
        }
      }

      var tools = {
        addCustomTag: function(preTag, postTag) {
          textAreaVal = getTextAreaSelection();
          answerInput.value = textAreaVal.pre + preTag + textAreaVal.selected + postTag + textAreaVal.post;
        }
      };


      $(window).scroll(function() {
        calculateArrowToTopStyle();
      });

      $(document).ready(function() {
        calculateArrowToTopStyle();
      });

      $(window).resize(function() {
        calculateArrowToTopStyle();
      });

      $('a[href*="#"]').on('click', function (e) {
      	e.preventDefault();

        var scrollTo = $($(this).attr('href')).offset().top - 60;

      	$('html, body').animate({
      		scrollTop: scrollTo
      	}, 500, 'linear', function() {
          waiting = false;
          calculateArrowToTopStyle();
        });
      });
	  
	  function increment(){
		if(document.getElementById("demo").innerHTML > 0){
			document.getElementById("demo").innerHTML = parseInt(document.getElementById("demo").innerHTML) -1;
		}
	  }
	  
	   function decrement(){
			document.getElementById("demo").innerHTML = parseInt(document.getElementById("demo").innerHTML) +1;
	  }

      $("form:not(#questionScore,#answerScore)").submit(function(event) {
          event.preventDefault();
          var form = $(this);
          var data = form.serialize();
          var formId = form.attr("id");
          var messageBox = $("#msg-postAnswer");
          if (typeof formId !== typeof undefined && formId !== false) {
            if (formId.startsWith("deleteAnswer")) {
              messageBox = $("#msg-"+formId);
            }
            else if (formId.startsWith("deleteQuestion")) {
              messageBox = $("#msg-deleteQuestion");
            }
          }
          var output = "";
          $.ajax({
              url: 'formHandler.php',
              type: 'post',
              dataType: 'json',
              data: data,
              success: function(result) {
                  try {
                      if(result.errors.length === 0) {
                        if (formId.startsWith("deleteQuestion")) {
                          var loc = location.href.replace("question.php", "index.php");
                          location.replace(loc.slice(0,loc.indexOf("?")));
                        }
                        else {
                          location.reload();
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
