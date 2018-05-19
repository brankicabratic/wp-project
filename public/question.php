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
            <div class="question-header">
              <?php
                $db = new Database;
                if (isset($_GET["id"])) {
                  $questionId = $_GET["id"];
                  $question = $db->getQuestion($questionId);
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
                <div class="tag">Matematika</div>
                <div class="tag">Informatika</div>
                <div class="tag">Databases</div>
              </div>
            </div>
            <?php 
              if (isset($_GET["id"])) {
                $question = $db->getQuestion($_GET["id"]);
                echo "<div class=\"question-content\">$question[Content]</div>";
              }
            ?>
            <div class="question-footer">
              <span class="score">
				
                <span class="reaction dislike"><button class="fas fa-caret-left" onclick="increment()"></button></span>
                <span id="demo">0</span>
                <span class="reaction like active"><button class="fas fa-caret-right" onclick="decrement()"></button></span>
              </span>
            </div>
          </div>
          <div class="row">
            <div class="col-1 d-none d-lg-block scroll-top-answers-side">
              <span class="arrow invisible back-to-top-button"><a title="Back to top" href="#the-question"><i class="fas fa-arrow-circle-up"></i></a></span>
            </div>
            <div class="col-lg-11">
              <h4>Ostavi odgovor:</h4>
              <div class="write-answer">
                <div class="form-result-box"></div>
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

                <div class="answer">
                  <div class="content">
                    Another solution that I have developed and might be worth considering: http://codility.com/demo/results/demoM658NU-DYR/
                  </div>
                  <div class="footer">
                    <div class="aligned-right">
                      <span class="score">
                        <span class="reaction dislike active"><i class="fas fa-caret-left"></i></span>
                        <span class="actual-score">0</span>
                        <span class="reaction like"><i class="fas fa-caret-right"></i></span>
                      </span>
                      Odgovorio <a href="#">peraPeric</a> 25. 01. 2018. u 14:52
                    </div>
                  </div>
                </div>

                <div class="answer">
                  <div class="content">
                    Another solution that I have developed and might be worth considering: http://codility.com/demo/results/demoM658NU-DYR/
                  </div>
                  <div class="footer">
                    <div class="aligned-right">
                      <span class="score">
                        <span class="reaction dislike active"><i class="fas fa-caret-left"></i></span>
                        <span class="actual-score">0</span>
                        <span class="reaction like"><i class="fas fa-caret-right"></i></span>
                      </span>
                      Odgovorio <a href="#">peraPeric</a> 25. 01. 2018. u 14:52
                    </div>
                  </div>
                </div>

                <div class="answer">
                  <div class="content">
                    Another solution that I have developed and might be worth considering: http://codility.com/demo/results/demoM658NU-DYR/
                  </div>
                  <div class="footer">
                    <div class="aligned-right">
                      <span class="score">
                        <span class="reaction dislike active"><i class="fas fa-caret-left"></i></span>
                        <span class="actual-score">0</span>
                        <span class="reaction like"><i class="fas fa-caret-right"></i></span>
                      </span>
                      Odgovorio <a href="#">peraPeric</a> 25. 01. 2018. u 14:52
                    </div>
                  </div>
                </div>

                <div class="answer">
                  <div class="content">
                    Another solution that I have developed and might be worth considering: http://codility.com/demo/results/demoM658NU-DYR/
                  </div>
                  <div class="footer">
                    <div class="aligned-right">
                      <span class="score">
                        <span class="reaction dislike active"><i class="fas fa-caret-left"></i></span>
                        <span class="actual-score">0</span>
                        <span class="reaction like"><i class="fas fa-caret-right"></i></span>
                      </span>
                      Odgovorio <a href="#">peraPeric</a> 25. 01. 2018. u 14:52
                    </div>
                  </div>
                </div>

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

      $("form").submit(function(event) {
          event.preventDefault();
          var form = $(this);
          var data = form.serialize();
          var messageBox = $(".form-result-box");
          var output = "";
          $.ajax({
              url: 'formHandler.php',
              type: 'post',
              dataType: 'json',
              data: data,
              success: function(result) {
                  try {
                      if(result.errors.length === 0) {
                          output = "<div class=\"alert alert-success\" role=\"alert\">Uspešno ste postavili odgovor.</div>";
                      }
                      else
                          output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.errors.join("<br>") + "</div>";
                  }
                  catch(err) {
                      output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa serverom, molimo pokušajte kasnije!</div>";
                  }
              },
              error: function() {
                  console.log("HEEHH");
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
