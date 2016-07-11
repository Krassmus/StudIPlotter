<?php

class StudIPlotter extends StudIPPlugin implements SystemPlugin {

    static public function preparePlotting($markup, $matches, $contents)
    {
        $id = md5(uniqid());
        $parsed = studip_utf8decode(json_decode(trim($contents)));
        if (!$parsed) {
            $contents = array(
                'target' => "#plot_".$id,
                'data' => array(array(
                    'fn' => trim($contents)
                ))
            );
            $contents = json_encode(studip_utf8encode($contents));
        } else {
            $parsed['target'] = "#plot_".$id;
            $contents = json_encode(studip_utf8encode($parsed));
        }
        return sprintf(
            '<div id="plot_%s" class="studiplotter"></div>
            <script>jQuery(function () { functionPlot(%s); }); </script>',
            $id,
            $contents
        );
    }

    public function __construct()
    {
        parent::__construct();
        PageLayout::addScript($this->getPluginURL()."/assets/d3.min.js", array());
        PageLayout::addScript($this->getPluginURL()."/assets/function-plot.js");
        StudipFormat::addStudipMarkup("plotter", '\[plot\]', '\[\/plot\]', "StudIPlotter::preparePlotting");
    }
}