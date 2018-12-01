<?php
// Search plugin, https://github.com/datenstrom/yellow-plugins/tree/master/search
// Copyright (c) 2013-2017 Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.

class YellowSearch
{
	const VERSION = "0.6.8";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
		$this->yellow->config->setDefault("searchLocation", "/search/");
		$this->yellow->config->setDefault("searchPaginationLimit", "5");
		$this->yellow->config->setDefault("searchPageLength", "250");
	}
	
	// Handle page content parsing of custom block
	function onParseContentBlock($page, $name, $text, $shortcut)
	{
		$output = null;
		if($name=="search" && $shortcut)
		{
			list($location) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("searchLocation");
			$output = "<div class=\"".htmlspecialchars($name)."\">\n";
			$output .= "<form class=\"search-form\" action=\"".$this->yellow->page->base.$location."\" method=\"post\">\n";
			$output .= "<input class=\"form-control\" type=\"text\" name=\"query\" placeholder=\"".$this->yellow->text->getHtml("searchButton")."\" />\n";
			$output .= "<input type=\"hidden\" name=\"clean-url\" />\n";
			$output .= "</form>\n";
			$output .= "</div>\n";
		}
		return $output;
	}
	
	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template")=="search")
		{
			if($this->yellow->isCommandLine()) $this->yellow->page->error(500, "Static website not supported!");
			$query = trim($_REQUEST["query"]);
			$tokens = array_slice(array_unique(array_filter(explode(' ', $query), "strlen")), 0, 10);
			if(!empty($tokens))
			{
				$this->yellow->page->set("titleHeader", $query." - ".$this->yellow->page->get("sitename"));
				$this->yellow->page->set("title", $this->yellow->text->get("searchQuery")." ".$query);
				$pages = $this->yellow->pages->clean();
				foreach($this->yellow->pages->index(false, false) as $page)
				{
					$searchScore = 0;
					$searchTokens = array();
					foreach($tokens as $token)
					{
						$score = substr_count(strtoloweru($page->getContent(true)), strtoloweru($token));
						if($score) { $searchScore += $score; $searchTokens[$token] = true; }
						if(stristr($page->getLocation(true), $token)) { $searchScore += 20; $searchTokens[$token] = true; }
						if(stristr($page->get("title"), $token)) { $searchScore += 10; $searchTokens[$token] = true; }
						if(stristr($page->get("tag"), $token)) { $searchScore += 5; $searchTokens[$token] = true; }
						if(stristr($page->get("author"), $token)) { $searchScore += 2; $searchTokens[$token] = true; }
					}
					if(count($tokens)==count($searchTokens))
					{
						$page->set("searchscore", $searchScore);
						$pages->append($page);
					}
				}
				$pages->sort("searchscore");
				$pages->pagination($this->yellow->config->get("searchPaginationLimit"));
				if($_REQUEST["page"] && !$pages->getPaginationNumber()) $this->yellow->page->error(404);
				$this->yellow->page->setPages($pages);
				$this->yellow->page->setLastModified($pages->getModified());
				$this->yellow->page->setHeader("Cache-Control", "max-age=60");
				$this->yellow->page->set("status", count($pages) ? "done" : "empty");
			} else {
				$this->yellow->page->set("status", "none");
			}
		}
	}
}

$yellow->plugins->register("search", "YellowSearch", YellowSearch::VERSION);
?>