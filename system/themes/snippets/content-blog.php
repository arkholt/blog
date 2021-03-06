<div class="content main">
<?php $yellow->page->set("entryClass", "entry") ?>
<?php if($yellow->page->isExisting("tag")): ?>
<?php foreach(preg_split("/,\s*/", $yellow->page->get("tag")) as $tag) { $yellow->page->set("entryClass", $yellow->page->get("entryClass")." ".$yellow->toolbox->normaliseArgs($tag, false)); } ?>
<?php endif ?>
<div class="<?php echo $yellow->page->getHtml("entryClass") ?>">
<div class="entry-header"><h1><?php echo $yellow->page->getHtml("titleContent") ?></h1></div>
<div class="entry-meta"><p><i class='fa fa-calendar'></i> <?php echo htmlspecialchars($yellow->page->getDate("published")) ?><!--</p><?php echo $yellow->text->getHtml("blogBy") ?> <p><?php $authorCounter = 0; foreach(preg_split("/,\s*/", $yellow->page->get("author")) as $author) { if(++$authorCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getPage("blog")->getLocation(true).$yellow->toolbox->normaliseArgs("author:$author")."\">".htmlspecialchars($author)."</a>"; } ?></p> -->
  <p><?php if($yellow->page->isExisting("tag")): ?>
  <p><!--<?php echo $yellow->text->getHtml("blogTag") ?> --> <i class='fa fa-tag'></i> <?php $tagCounter = 0; foreach(preg_split("/,\s*/", $yellow->page->get("tag")) as $tag) { if(++$tagCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getPage("blog")->getLocation(true).$yellow->toolbox->normaliseArgs("tag:$tag")."\">".htmlspecialchars($tag)."</a>"; } ?></p>
  <?php endif ?></p></div>
<div class="entry-content"><?php echo $yellow->page->getContent() ?></div>
<div class="entry-footer">
<!--<?php if($yellow->page->isExisting("tag")): ?>
<p><?php echo $yellow->text->getHtml("blogTag") ?> <i class='fa fa-tag'></i> <?php $tagCounter = 0; foreach(preg_split("/,\s*/", $yellow->page->get("tag")) as $tag) { if(++$tagCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getPage("blog")->getLocation(true).$yellow->toolbox->normaliseArgs("tag:$tag")."\">".htmlspecialchars($tag)."</a>"; } ?></p>
<?php endif ?>-->
</div>
</div>
<?php echo $yellow->page->getExtra("comments") ?>
</div>
