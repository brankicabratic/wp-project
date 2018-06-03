<?php
  require_once 'db_utils.php';
  require_once 'handlers/user_handler.php';
  require_once 'parts.php';


  
  function printQuestion(&$question) {
    $dateFormat = date("d. m. Y. \u H:i", strtotime($question[COL_POST_POSTED]));
    echo "<div class=\"question\">
      <a href=\"question.php?id={$question[COL_QUESTION_ID]}\"><span class=\"heading\">{$question[COL_QUESTION_HEADER]}</span></a>";
    if(!empty($question["TAGS"])) {
      echo "<span class=\"tags\">";
      foreach($question["TAGS"] as &$tag){
        echo "<a href=\"index.php?tagName={$tag[COL_TAG_NAME]}\"><span class=\"tag\">{$tag[COL_TAG_NAME]}</span></a>";
      }
      echo "</span>";
    }
    echo "<span class=\"author\">Pitao <a href=\"profile.php?user={$question[COL_USER_USERNAME]}\">{$question[COL_USER_USERNAME]}</a> {$dateFormat} | odgovora {$question["NUMBEROFASNWERS"]} | ocena {$question["SCORE"]}</span>
      </div>";
  }

  function printPageLinks($numAllPages, $page) {
    echo "<div class=\"pages\">";
    if ($numAllPages > 10) {
      $from = $page - 5;
      $to = $page + 4;
      while ($from < 1) {
        $from = $from + 1;
        $to = $to + 1;
      }
      while ($to > $numAllPages) {
        $from = $from - 1;
        $to = $to - 1;
      }
    }
    else {
      $from = 1;
      $to = $numAllPages;
    }
    for ($i = $from; $i <= $to; $i++) {
      $class = $i == $page ? "page-link-selected" : "page-link";
      $url = $_SERVER['REQUEST_URI'];
      if (empty($_GET) && $i != $page) {
        $url = $_SERVER['REQUEST_URI']."?page={$i}";
      }
      else {
        $url = isset($_GET["page"]) ? preg_replace("%page=[/^[1-9][0-9]*|0$/]%", "page=$i", $url) : $_SERVER['REQUEST_URI']."&page={$i}" ;
      }
      echo $i == $page ? "<span class=\"page-link-selected\"> $i </span>" : "<a href=\"{$url}\"><span class=\"page-link\"> $i </span></a>";
    }
    echo "</div>";
  }

  $db = new Database;

  $numQuestions = $db->getNumberOfQuestions(isset($_GET["category"]) ? $_GET["category"] : "0");
  $step = isset($_GET["step"]) && $_GET["step"] > 0 ? $_GET["step"] : 10;
  $numAllPages = ceil($numQuestions / $step); 
  $page = isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $numAllPages ? $_GET["page"] : 1;


  if (isset($_GET["filterQuestions"])) {
    $questions = $db->getNthPageQuestions($page, $step, $_GET["filterType"], $_GET["order"], $_GET["nameSearch"], $_GET["tagSearch"], $_GET["category"]);
  } else if (isset($_GET["tagName"])) {
    $questions = $db->getNthPageQuestions($page, $step, "dateOfCreation", 0, "", $_GET["tagName"], 0);
  } else {
    $questions = $db->getNthPageQuestions($page, $step);
  }
  foreach($questions as &$question) {
    $question["TAGS"] = $db->getTagsRelatedToQuestion($question[COL_QUESTION_ID]);
    $question["SCORE"] = $db->getPostsScore($question[COL_QUESTION_ID]);
    $question["NUMBEROFASNWERS"] = $db->countQuestionsAnswers($question[COL_QUESTION_ID]);
  }

  function insertCategories($insert) {
    $db = new Database;
    $categories = $db->getAllCategories();
    foreach ($categories as $category) {
      if ($insert && $category[COL_CATEGORY_ID] == 0) continue;
      $selected = $category[COL_CATEGORY_ID] == 0 ? "selected" : "";
      echo "<option value=\"{$category[COL_CATEGORY_ID]}\" $selected >{$category[COL_CATEGORY_NAME]}</option>";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php printIncludes('Pitanja - PMFOverflow') ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body>
  <?php includeNavigation() ?>

  <div class="container main-container">
    <?php includeQuoteHeader() ?>

    <div class="row">
      <?php 
      if (isset($_SESSION["message"])) {
        echo $_SESSION["message"];
        unset($_SESSION["message"]);
      }
      ?>
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
                  <input type="text" class="form-control" name="naslov" placeholder="Naslov pitanja">
                  <input type="hidden" name="formType" value="askQuestion">
                  <textarea name="sadrzaj" class="form-control" rows="8" cols="80" placeholder="Tekst pitanja"></textarea>
                  <div id="tag-block"></div>
                  <input type="text" id="add-tag" name="tag" list="tag-list" placeholder="Dodaj tag" autocomplete="off">
                  <datalist id="tag-list"></datalist>
                  <div class="select-header">
                      <p>Kategorija:</p>
                    </div>
                    <select class="form-control" name="category">
                      <?php insertCategories(true);?>
                    </select>
                  <div class="submit-container">
                  <?php 
                    if ($user) {
                      echo '<input type="submit" class="btn btn-primary" name="" value="Pitaj">'; 
                    } else {
                      echo '<input type="submit" class="btn btn-primary" data-toggle="popover" title="Info" data-content="Access is denied. User need to be registered." name="" value="Pitaj">';
                    }
                  ?>
                    
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
                    <select class="form-control" id="filterType" name="filterType">
                      <option value="dateOfCreation">Datumu objave</option>
                      <option value="authorScore">Oceni autora</option>
                      <option value="questionScore">Oceni pitanja</option>
                      <option value="noOfAnswers">Broju odgovora</option>
                      <option value="averageQuestionScore">Prosečnoj oceni pitanja</option>
                      <option value="modificationDate">Datumu promene</option>
                    </select>
                    <script type="text/javascript">
                      document.getElementById('filterType').value = "<?php echo htmlspecialchars($_GET['filterType']);?>";
                    </script>
                  </div>
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>U redosledu:</p>
                    </div>
                    <select class="form-control" id="order" name="order">
                      <option value="0">Opadajuće</option>
                      <option value="1">Rastuće</option>
                    </select>
                    <script type="text/javascript">
                      document.getElementById('order').value = "<?php echo htmlspecialchars($_GET['order']);?>";
                    </script>
                  </div>
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>Prikaži po:</p>
                    </div>
                    <select class="form-control" id="step" name="step">
                      <option value="5">5</option>
                      <option value="10" selected="true">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                    </select>
                    <script type="text/javascript">
                      document.getElementById('step').value = "<?php echo htmlspecialchars($_GET['step']);?>";
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>Pretraga po naslovu pitanja:</p>
                    </div>
                    <input type="text" name="nameSearch" value="<?php if(isset($_GET["nameSearch"])) echo htmlspecialchars($_GET["nameSearch"]) ?>" class="form-control">
                  </div>
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>Pretraga po tagovima:</p>
                    </div>
                    <input type="text" name="tagSearch" value="<?php if(isset($_GET["tagSearch"])) echo htmlspecialchars($_GET["tagSearch"]); elseif(isset($_GET["tagName"])) echo htmlspecialchars($_GET["tagName"]); ?>"class="form-control">
                  </div>
                  <div class="col-lg-4">
                    <div class="select-header">
                      <p>Kategorija:</p>
                    </div>
                    <select class="form-control" id="category" name="category">
                      <?php insertCategories(false);?>
                    </select>
                    <script type="text/javascript">
                      document.getElementById('category').value = "<?php echo htmlspecialchars($_GET['category']);?>";
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    
                  </div>
                  <div class="col-lg-4">
                    
                  </div>
                  <div class="col-md-4">
                    <input type="submit" class="btn btn-primary" name="filterQuestions" value="Primeni">
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
        <?php printPageLinks($numAllPages, $page);?>
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
            <?php
              $users = getTopActiveUsers();
              foreach($users as $user) {
                echo "<li><a href='profile.php?user=".$user["username"]."' >".$user["username"]."</a>(".$user["count_msg"].")</li>";
              }
            ?>
          </ul>
        </div>
        <div class="side-block">
          Najpopularnije kategorije:
          <ul>
            <?php
              $category = getPopularCategory();
              foreach($category as $cat) {
                echo "<li><a href=\"?filterType=dateOfCreation&order=0&step=10&nameSearch=&tagSearch=&category={$cat[COL_CATEGORY_ID]}&filterQuestions=Primeni\">".$cat["name"]."(".$cat["count_cat"].")</a></li>";
              }
            ?>
          </ul>
        </div>
      </div>
    </div>

    <?php includeFooter() ?>
  </div>

  <?php includeScripts() ?>
  <script>

    $(document).ready(function(){
      $('[data-toggle="popover"]').popover();     
    });

    Array.prototype.findTag = function(tagName) {
      for(var i = 0; i < this.length; i++)
        if(this[i].name === tagName)
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
        this.target.append("<div class=\"tag\">" + tag.name + " <i class=\"fas fa-times\" onclick=\"removeTag('" + tag.name + "')\" style=\"cursor: pointer\"></i></div>");
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
        this.target.append("<option value=\"" + tag.Name + "\">" + tag.Name + "</tag>")
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
      if(choosenTags.length >= 5)
        return;
      if (indexOfTagInChoosenTags !== -1)
        return;
      if(!indexOfTag === -1) {
        avaliableTags.splice(indexOfTag, 1);
      }
      choosenTags.push(Object.assign({}, { name : tag }));
      tagList.update();
      tagBlock.update();
      tagInput.blur();
      tagInput.val("");
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
      if (keyCode === 13) {
        var inputVal = $(this).val();
        var tagsSplit = inputVal.split(/[\s,]+/);
        var len = tagsSplit.length;
        for (var i = 0; i < len; i++) {
          if (tagsSplit[i] === "")
            return;
          addTag(tagsSplit[i]);
        }
      }
    });

    tagList.update();

    $("form[method=\"post\"]").submit(function(event) {
      event.preventDefault();
      var form = $(this);
      var data = form.serialize();
      var messageBox = $(".form-result-box");
      var output = "";
      data += "&&tags=" + choosenTags.map(function(el) { return el.name; }).join();
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
              window.location.href = "question.php?id=" + result["succ"][1];
              //output = "<div class=\"alert alert-success\" role=\"alert\">" + result.succ.join("<br>") + "</div>";
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
          //$('html, body').animate({scrollTop:0}, 500);
        }
      });
    });
  </script>
</body>
</html>
