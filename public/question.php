<?php require_once 'parts.php' ?>
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
              <h1>AfterUpdate event on txt field in form to update other tables with the same field</h1>
              <small>Pitao <a href="#">peraPeric</a> 26. 01. 2018. u 22:25</small>
              <div class="tags">
                <div class="tag">Matematika</div>
                <div class="tag">Informatika</div>
                <div class="tag">Databases</div>
              </div>
            </div>
            <div class="question-content">
              My database was created from a DOS system by importing the .dbf files into Access 2016. After completing the setup of the Access database I will have to re-import updated .dbf files. The personal information (first, last, ssn, address, etc.) of customers are listed in multiple tables rather than using a primary ID so about 10+ tables contain the same person and details. A customer can be listed multiple times in some tables such as the life and health claims tables (when a person files multiple claims).
              <br><br>
              Each of these tables is the source of a form (i.e. customers.tbl = Customers form). I want to be able to make a personal information update in any of the forms and have it reflected everywhere...if I change the address in my Health Claims form, I would like that change to be reflected in my Life Claims form/table as well as everywhere else for the same SSN.
              <br><br>
              How should the AfterUpdate event for a field (textbox) be written so that ut updates in multiple tables? Is there a better way than using AfterUpdate? After searching I am finding most updates for selecting from a combo box and filtering from there but that is not what I want.
              <br><br>
              Is the macro event, "requery" necessary or not really since I am not trying to requery/re-load my form? I am just changing my data.
              <br><br>
              Or should I reconfigure the database so that all forms using personal information are pulled from one table joined on primary and foreign keys? I will have to re-import updated .dbf files one final time after building the forms---just the data will be updated.
              <br><br>
              In advance, thank you for your help and guidance.
            </div>
            <div class="question-footer">
              <span class="score">
                <span class="reaction dislike active"><i class="fas fa-caret-left"></i></span>
                <span class="actual-score">0</span>
                <span class="reaction like"><i class="fas fa-caret-right"></i></span>
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
                <form method="post" id="answer-form">
                    <div class="text-formating-tools">
                      <span class="tool" onclick="tools.addCustomTag(' [superscript]', '[/superscript] ')"><i class="fas fa-superscript"></i></span>
                      <span class="tool" onclick="tools.addCustomTag('[code lang=\'html\']\n', '\n[/code]')"><i class="fas fa-code"></i></span>
                    </div>
                    <textarea name="answer-content" spellcheck="false" placeholder="Odgovor"></textarea>
                    <div class="submit-group">
                      <input type="submit" class="btn btn-primary" name="" value="Odgovori">
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
    </script>
  </body>
</html>