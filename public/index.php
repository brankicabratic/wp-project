<?php
  require_once 'db_utils.php';
  require_once 'parts.php';

  function printQuestion(&$question) {
    $dateFormat = date("d. m. Y. \u H:i", strtotime($question[COL_POST_POSTED]));
    echo "<div class=\"question\">
      <a href=\"question.php?id={$question[COL_QUESTION_ID]}\"><span class=\"heading\">{$question[COL_QUESTION_HEADER]}</span></a>";
    if(!empty($question["TAGS"])) {
      echo "<span class=\"tags\">";
      foreach($question["TAGS"] as &$tag)
        echo "<a href=\"tag.php?name={$tag[COL_TAG_NAME]}\"><span class=\"tag\">{$tag[COL_TAG_NAME]}</span></a>";
      echo "</span>";
    }
    echo "<span class=\"author\">Pitao <a href=\"profile.php?user={$question[COL_USER_USERNAME]}\">{$question[COL_USER_USERNAME]}</a> {$dateFormat} | odgovora {$question["NUMBEROFASNWERS"]} | ocena {$question["SCORE"]}</span>
      </div>";
  }

  $db = new Database;
  $questions = $db->getNthPageQuestions(1, 5);
  foreach($questions as &$question) {
    $question["TAGS"] = $db->getTagsRelatedToQuestion($question[COL_QUESTION_ID]);
    $question["SCORE"] = $db->getPostsScore($question[COL_QUESTION_ID]);
    $question["NUMBEROFASNWERS"] = $db->countQuestionsAnswers($question[COL_QUESTION_ID]);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php printIncludes('Pitanja - PMFOverflow') ?>
</head>
<body>
  <?php includeNavigation() ?>

  <div class="container main-container">
    <?php includeQuoteHeader() ?>

    <div class="row">
      <div class="col-md-8">
        <div id="ask-filter-container">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="apply-filter-tab" data-toggle="tab" href="#apply-filter" role="tab" aria-controls="apply-filter" aria-selected="false">Primeni filter</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="ask-question-tab" data-toggle="tab" href="#ask-question" role="tab" aria-controls="ask-question" aria-selected="true">Postavi pitanje</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade" id="ask-question" role="tabpanel" aria-labelledby="ask-question-tab">

              <form method="post">
                <div class="form-result-box"></div>
                <div class="form-group">
                  <input type="text" class="form-control" name="" value="" placeholder="Naslov pitanja">
                  <input type="hidden" name="formType" value="askQuestion">
                  <textarea name="name" class="form-control" rows="8" cols="80" placeholder="Tekst pitanja"></textarea>
                  <div id="tag-block"></div>
                  <input type="text" id="add-tag" name="" value="" list="tag-list" placeholder="Dodaj tag" autocomplete="off">
                  <datalist id="tag-list"></datalist>
                  <div class="submit-container">
                    <input type="submit" class="btn btn-primary" name="" value="Pitaj">
                  </div>
                </div>
              </form>
            </div>

            <div class="tab-pane fade show active" id="apply-filter" role="tabpanel" aria-labelledby="apply-filter-tab">
              <form id="filter-form" method="get">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>Sortiraj prema:</p>
                    </div>
                    <select class="form-control" name="">
                      <option value="">Datumu objave</option>
                      <option value="">Oceni korisnika</option>
                    </select>
                  </div>
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>U redosledu:</p>
                    </div>
                    <select class="form-control" name="">
                      <option value="">Opadajuće</option>
                      <option value="">Rastuće</option>
                    </select>
                  </div>
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>Prikaži po:</p>
                    </div>
                    <select class="form-control" name="">
                      <option value="">25</option>
                      <option value="">50</option>
                    </select>
                    <input type="submit" class="btn btn-primary" name="" value="Primeni">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div id="questions">
          <?php
            foreach($questions as &$question)
              printQuestion($question);
          ?>
        </div>
      </div>

      <div class="col-md-4 hidden-lg-down">
        <div class="side-block">
          Najcesce teme protekle nedelje:
          <ul>
            <li>Matematika</li>
            <li>Informatika</li>
          </ul>
        </div>
        <div class="side-block">
          Najaktivniji korisnici:
          <ul>
            <li>PeraPeric</li>
            <li>NemanjaMarkovic</li>
          </ul>
        </div>
      </div>
    </div>

    <?php includeFooter() ?>
  </div>

  <?php includeScripts() ?>
  <script>
    Array.prototype.findTag = function(tagName) {
      for(var i = 0; i < this.length; i++)
        if(this[i].<?php echo COL_TAG_NAME ?> === tagName)
          return i;
      return -1;
    };
    var avaliableTags = <?php echo json_encode($db->getAvaliableTags()) ?>;
    var choosenTags = [];
    var tagInput = $("#add-tag");
    var tagBlock = {
      target: $("#tag-block"),
      reset: function() {
        this.target.html("");
      },
      add: function(tag) {
        this.target.append("<div class=\"tag\">" + tag.<?php echo COL_TAG_NAME ?> + " <i class=\"fas fa-times\" onclick=\"removeTag('" + tag.<?php echo COL_TAG_NAME ?> + "')\" style=\"cursor: pointer\"></i></div>");
      },
      update: function() {
        this.reset();
        var len = choosenTags.length;
        for(var i = 0; i < len; i++)
          this.add(choosenTags[i]);
      }
    };
    var tagList = {
      target: $("#tag-list"),
      reset: function() {
        this.target.html("");
      },
      add: function(tag) {
        this.target.append("<option value=\"" + tag.<?php echo COL_TAG_NAME ?> + "\">" + tag.<?php echo COL_TAG_NAME ?> + "</tag>")
      },
      update: function() {
        this.reset();
        var len = avaliableTags.length;
        for(var i = 0; i < len; i++)
          this.add(avaliableTags[i]);
      }
    };

    var addTag = function(tag) {
      var indexOfTag = avaliableTags.findTag(tag), indexOfTagInChoosenTags = choosenTags.findTag(tag);
      if(indexOfTag === -1 || indexOfTagInChoosenTags !== -1)
        return;
      if(choosenTags.length >= 5)
        return;
      choosenTags.push(Object.assign({}, avaliableTags[indexOfTag]));
      avaliableTags.splice(indexOfTag, 1);
      tagList.update();
      tagInput.blur();
      tagInput.val("");
      tagBlock.update();
    };

    var removeTag = function(tag) {
      var indexOfTag = choosenTags.findTag(tag);
      if(indexOfTag === -1)
        return;
      avaliableTags.push(Object.assign({}, choosenTags[indexOfTag]));
      choosenTags.splice(indexOfTag, 1);
      tagBlock.update();
      tagList.update();
    };

    $('#add-tag').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13)
        addTag($(this).val());
    });

    tagList.update();

    $("form[method=\"post\"]").submit(function(event) {
      event.preventDefault();
      var form = $(this);
      var data = form.serialize();
      var messageBox = $(".form-result-box");
      var output = "";
      data += "&&tags=" + choosenTags.map(function(el) { return el.<?php echo COL_TAG_ID ?>; }).join();
      $.ajax({
        url: 'formHandler.php',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function(result) {
          console.log(result);
          try {
            if(result.errors.length === 0) {
              // OTVORITI KORISNIKOVO PITANJE
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
          $('html, body').animate({scrollTop:0}, 500);
        }
      });
    });
  </script>
</body>
</html>
