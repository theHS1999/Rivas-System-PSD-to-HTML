<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="fa-IR" xml:lang="fa"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link rel="stylesheet" type="text/css" href="{$mainStyle}" media="screen" /><link rel="stylesheet" type="text/css" href="{$mainPrintStyle}" media="print" /><link rel="stylesheet" type="text/css" href="{$calendarStyle}" media="screen" /><link rel="stylesheet" type="text/css" href="{$formStyle}" media="screen" /><link rel="stylesheet" type="text/css" href="{$jqPlotStyle}" media="screen" /><link rel="stylesheet" type="text/css" href="{$JQUIStyle}" media="screen" /><link rel="stylesheet" type="text/css" href="{$CustomizeJQUIStyle}" media="screen" /><script src="{$jQuery}" type="text/javascript"></script><script src="{$formJS}" type="text/javascript"></script><script src="{$mainJS}" type="text/javascript"></script><script src="{$AJAX}" type="text/javascript"></script><script src="{$JQUI}" type="text/javascript"></script><script src="{$JSCalendar}" type="text/javascript"></script><script src="{$JSShamsi}" type="text/javascript"></script><title>{$title}</title></head><body><div id="container"><div id="headerBlock">{if isset($headerBlock)}{section name=i loop=$headerBlock}{$headerBlock[i]}{/section}{/if}</div><div id="mainBlock"><div id="mainBlockHeader"></div><div id="mainBlockContent">{if isset($dateBlock)}<div id="dateBlock">{section name=i loop=$dateBlock}{$dateBlock[i]}{/section}</div>{/if}{if isset($rightBlock)}<div id="rightBlock"><div id="rightBlockHeader"></div><div id="rightBlockContent">{section name=i loop=$rightBlock}{$rightBlock[i]}{/section}</div><div id="rightBlockFooter"></div></div>{/if}<div id="centerBlock"><div id="centerBlockHeader"></div><div id="centerBlockContent"><div id="centerBlockDiv">{if isset($centerBlock)}{section name=i loop=$centerBlock}{$centerBlock[i]}{/section}{/if}</div></div><div id="centerBlockFooter"></div></div><div id="footerBlockDiv">{if isset($footerBlock)}{section name=i loop=$footerBlock}{$footerBlock[i]}{/section}{/if}</div></div><div id="mainBlockFooter"></div></div></div><div id="lf"></div></body></html>