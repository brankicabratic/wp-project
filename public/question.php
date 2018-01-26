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
              <a href="#answers">ans</a>
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
              <span class="arrow invisible"><i class="fas fa-arrow-circle-up"></i></span>
            </div>
            <div class="col-lg-11">
              <div id="answers">
                <h4>Ostavi odgovor:</h4>
                <div class="ask">
                  <form method="post">
                      <div class="text-formating-tools">
                        <span class="tool"><i class="fas fa-trash-alt"></i></span>
                        <span class="tool"><i class="fas fa-superscript"></i></span>
                      </div>
                      <textarea name="answer-content" spellcheck="false" placeholder="Odgovor"></textarea>
                      <div class="submit-group">
                        <input type="submit" class="btn btn-primary" name="" value="Odgovori">
                      </div>
                  </form>
                </div>
              </div>
            </div>
          </div>


        </div>
        <div class="col-lg-2"><!-- Sometime in the future something may even be here! It only exists for filling up the space at the moment. --></div>
      </div>

      <?php includeFooter() ?>
    </div>

    <?php includeScripts() ?>
    <script type="text/javascript">
      var answersContainer = $("#answers");
      var scrollTopFixedArrow = $(".scroll-top-answers-side .arrow");

      $(window).scroll(function (){
        var answContPos = answersContainer.offset().top - 60;
        console.log(answersContainer.height());
        var relPos = $(window).scrollTop() - answContPos;
        if(relPos < 0)
          scrollTopFixedArrow.addClass("invisible");
        else {
          scrollTopFixedArrow.removeClass("invisible");
        }
      });
    </script>
  </body>
</html>
