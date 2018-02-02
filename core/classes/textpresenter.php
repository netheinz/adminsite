<?php
/**
 * Presenter for admin text & html snippets
 * @copyright (c) 2016, Heinz K, Tech College
 */
class textPresenter {
    /**
     * Metode til at vise panel i toppen af CMS modul sider
     * @param string $strModuleName Modulets navn (Eks: Brugere, Events, Produkter... )
     * @param string $strModuleMode Modulets mode (Eks: Oversigt, Rediger, Opret ny...)
     * @param array $arrButtonPanel Array med knapper
     * @return string
     */
    static function presentpanel($strModuleName = "Modul", $strModuleMode = "Oversigt", $arrButtonPanel = array()) {
        $accHtml = ""; /* Accumulated html string */

        /* Indsætter titler */
        $accHtml .= '<div class="mainheader">' . "\n" .
                    '  <div class="pull-left">' .  "\n" .
                    '   <h1>' . $strModuleName . '</h1>' .  "\n" .
                    '   <h2>' . $strModuleMode . '</h2>' .  "\n" .
                    '  </div>';

        /* Bygger knap panel */
        if(count($arrButtonPanel) > 0) {
            $accHtml .= '<div class="pull-right">';
            foreach ($arrButtonPanel as $button) {
                $accHtml .= $button;
            }
            $accHtml .= '</div>';
        }

        $accHtml .= '</div>';

        return $accHtml;
    }
}