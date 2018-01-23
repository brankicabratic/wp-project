<?php require_once 'parts.php' ?>
<!DOCTYPE html>
<html>
  <head>
    <?php printIncludes('Pitanje') ?>
  </head>
  <body>

    <div class="container main-container">
      <?php includeNavigation() ?>

      <div id="the-question">
        <h1>HTML & script for a form without storing to database (wordpress)</h1>
        <div class="row">
          <div class="col-md-8">
            <div class="question-text">
              I'd like to add a form for my website, which consists of, say, 4 text fields and a submit button.
              <br><br>
              After clicking the submit button, a user should see the same fields on the same page but formatted differently with some other text. The fields' data should not be stored into the database (because I don't need it in any way in the future, I'm not going to send emails or whatever).
              <br><br>
              The fields' data is used for this particular user at this moment and that's all. If the user leaves the page, the information entered is gone.
              <br><br>
              It's a sort of an online self-help exercise.
              <br><br>
              I guess it should be a very simple code and script, but I don't have enough experience. Will appreciate any help.
              <br><br>
              My HTML:<br>
<pre><code>
&lt;form action="" method="post"&gt;
Question 1
Answer text
&lt;input type="text" name="rebt-s1"&gt;

Question 2
Answer text
&lt;input type="text" name="rebt-s2"&gt;

Question 3
Answer text
&lt;input type="text" name="rebt-s3"&gt;

Question 4
Answer text
&lt;input type="text" name="rebt-s4"&gt;
&lt;input type="submit" value="save"&gt;
&lt;/form&gt;
</code></pre>
</div>
          </div>
          <div class="col-md-4">
            <div class="question-info">
              <p>
                Pitao <a href="profile.php?user=peraPeric">Pera Peric</a>
                <br>
                22. 01. 2018. u 15:52
                <br>
                <span class="tags">
                  <span class="tag">Matematika</span>
                  <span class="tag">Informatika</span>
                  <span class="tag">C</span>
                  <span class="tag">Javascript</span>
                </span>
                <hr>
                <span class="score"><small>Ocena korisnika:</small><br><span class="reaction dislike"><i class="fas fa-caret-left"></i></span> <span class="actual-score">231</span> <span class="reaction like"><i class="fas fa-caret-right"></i></span></span>
                <!--
                <div class="reactions">
                  <span class="reaction like"><i class="far fa-thumbs-up"></i> Svidja mi se</span>
                  <span class="reaction dislike"><i class="far fa-thumbs-down"></i> Ne svidja mi se</span>
                </div>
              -->
              </p>
            </div>
          </div>
        </div>
      </div>

      <?php includeFooter() ?>
    </div>

    <?php includeScripts() ?>
    <script type="text/javascript">
      $("span.reaction").on("click", function() {
        var el = $(this);
        if(el.hasClass("active"))
          return;
        var par = $(this).parent();
        var score = $(this).parent().find(".actual-score");
        var scoreVal = parseInt(score.html());
        var inc = 0;
        if(el.hasClass("like"))
          inc = 1;
        else if(el.hasClass("dislike"))
          inc = -1;
        par.find("span.reaction").removeClass("active");
        score.html(parseInt(score.html()) + inc);
        el.addClass("active");
      });
    </script>
  </body>
</html>
