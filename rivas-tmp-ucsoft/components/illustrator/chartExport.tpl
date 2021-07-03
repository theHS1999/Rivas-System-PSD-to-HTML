<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-IR" xml:lang="fa">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="{$mainStyle}" media="screen" />
<link rel="stylesheet" type="text/css" href="{$mainPrintStyle}" media="print" />
<link rel="stylesheet" type="text/css" href="{$jqPlotStyle}" media="screen" />
<link rel="stylesheet" type="text/css" href="{$JQUIStyle}" media="screen" />
<link rel="stylesheet" type="text/css" href="{$CustomizeJQUIStyle}" media="screen" />
<script src="{$jQuery}" type="text/javascript"></script>
<script src="{$mainJS}" type="text/javascript"></script>
<script src="{$AJAX}" type="text/javascript"></script>
<script src="{$JQUI}" type="text/javascript"></script>

<title>{$title}</title>
</head>
<body>

                    <div id="chartExport">
                        {if isset($centerBlock)}
                            {section name=i loop=$centerBlock}
                                {$centerBlock[i]}
                            {/section}
                        {/if}
                    </div>

</body>
</html>
