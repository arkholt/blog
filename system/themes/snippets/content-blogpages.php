<div class="content main">
<?php if($yellow->page->isExisting("titleBlog")): ?>
<h1><?php echo $yellow->page->getHtml("titleBlog") ?></h1>
<?php endif ?>
<?php foreach($yellow->page->getPages() as $page): ?>
<?php $page->set("entryClass", "entry") ?>
<?php if($page->isExisting("tag")): ?>
<?php foreach(preg_split("/,\s*/", $page->get("tag")) as $tag) { $page->set("entryClass", $page->get("entryClass")." ".$yellow->toolbox->normaliseArgs($tag, false)); } ?>
<?php endif ?>
<div class="<?php echo $page->getHtml("entryClass") ?>">
<div class="entry-header"><h1><a href="<?php echo $page->getLocation(true) ?>"><?php echo $page->getHtml("title") ?></a></h1></div>
<div class="entry-meta"><p><i class='fa fa-calendar'></i> <?php echo htmlspecialchars($page->getDate("published")) ?></p> <!--<?php echo $yellow->text->getHtml("blogBy") ?>-->
  <!--<p><?php $authorCounter = 0; foreach(preg_split("/,\s*/", $page->get("author")) as $author) { if(++$authorCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getLocation(true).$yellow->toolbox->normaliseArgs("author:$author")."\">".htmlspecialchars($author)."</a>"; } ?></p>-->
  <p><?php if($yellow->page->isExisting("tag")): ?>
  <p><i class='fa fa-tag'></i> <?php $tagCounter = 0; foreach(preg_split("/,\s*/", $yellow->page->get("tag")) as $tag) { if(++$tagCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getPage("blog")->getLocation(true).$yellow->toolbox->normaliseArgs("tag:$tag")."\">".htmlspecialchars($tag)."</a>"; } ?></p>
  <?php endif ?></p></div>
<div class="entry-content"><?php echo $yellow->toolbox->createTextDescription($page->getContent(), $yellow->config->get("blogPageLength"), false, "<!--more-->", " <a href=\"".$page->getLocation(true)."\">".$yellow->text->getHtml("blogMore")."</a>") ?></div>
<div id="entry-footer"><i class="fa fa-comment-o"></i> <?php <a href=\"".$page->getLocation(true)."\"#disqus_thread">Comments</a>?></div>
</div>
<?php endforeach ?>
<?php $yellow->snippet("pagination", $yellow->page->getPages()) ?>
</div>
