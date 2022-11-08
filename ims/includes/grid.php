<?php
require_once('settings.php');

function displayGrid($stmt = null, $headers = null, $leftButtons = null, $rightButtons = null, $data = null) {

  if($data) {
    $data_array = array();
    $left_button_array = array();
    $html = "";

    $html .= "<table class='table'>
            <tr>";

    if(!empty($leftButtons)) {
      foreach($leftButtons as $button) {
        $html .= "<th></th>";
      }
    }

    foreach($headers as $header => $headerData) {
      $html .= "<th>$header</th>";

      $data_array[] = $headerData;
    }

    if(!empty($rightButtons)) {
      foreach($rightButtons as $button) {
        $html .= "<th></th>";
      }
    }

    $html .= "</tr>";

      foreach($data AS $row)
      {

        $html .= "<tr>";

        if(!empty($leftButtons)) {
          foreach($leftButtons as $button_header => $button_link) {

            if(strpos($button_link, '**') !== false) {
              $explode = explode("**", $button_link);
              $link = $explode[0];
              $link_data = $explode[1];
            } else {
              $link = $button_link;
              $link_data = "";
            }

            if($link_data) {
              if(strpos($button_link, '?') !== false) {
                $explode = explode("?", $link);
                $link = $explode[0] . "?" . $explode[1] . $row[$link_data];
              }
            }
             if($link != "" && $link != " ") {
               $html .= "<td class='gridButtonTd'><a href='$link'><input class='btn btn-primary leftGridButton' type='button' value='$button_header'/></a></td>";
             } else {
               $html .= "<td class='gridButtonTd'><div class='btn btn-primary leftGridButton'>$button_header</div></td>";
             }
          }
        }

        for($i = 0; $i < count($data_array); $i++) {
          $html .= "<td class='gridData'>" . $row[$data_array[$i]] . "</td>";
        }

        if(!empty($rightButtons)) {
          foreach($rightButtons as $button_header => $button_link) {

            if(strpos($button_link, '**') !== false) {
              $explode = explode("**", $button_link);
              $link = $explode[0];
              $link_data = $explode[1];
            } else {
              $link = $button_link;
              $link_data = "";
            }

            if($link_data) {
              if(strpos($button_link, '?') !== false) {
                $explode = explode("?", $link);
                $link = $explode[0] . "?" . $explode[1] . $row[$link_data];
              }
            }

            if($link != "" && $link != " ") {
              $html .= "<td class='gridButtonTd'><a href='$link'><input class='btn btn-danger rightGridButton' type='button' value='$button_header'/></a></td>";
            } else {
              $html .= "<td class='gridButtonTd'><div class='btn btn-danger rightGridButton'>$button_header</div></td>";
            }
          }
        }

        $html .= "</tr>";
      }

    $html .= "</table>";

    return $html;

  } else {

    $data_array = array();
    $left_button_array = array();

    echo "<table class='table'>
            <tr>";

    if(!empty($leftButtons)) {
      foreach($leftButtons as $button) {
        echo "<th></th>";
      }
    }

    foreach($headers as $header => $headerData) {
      echo "<th>$header</th>";

      $data_array[] = $headerData;
    }

    if(!empty($rightButtons)) {
      foreach($rightButtons as $button) {
        echo "<th></th>";
      }
    }

    echo "</tr>";

      while ($row = $stmt->fetch())
      {
        echo "<tr data-rowId='$row[0]'>";

        if(!empty($leftButtons)) {
          foreach($leftButtons as $button_header => $button_link) {

            if(strpos($button_link, '**') !== false) {
              $explode = explode("**", $button_link);
              $link = $explode[0];
              $link_data = $explode[1];
            } else {
              $link = $button_link;
              $link_data = "";
            }

            if($link_data) {
              if(strpos($button_link, '?') !== false) {
                $explode = explode("?", $link);
                $link = $explode[0] . "?" . $explode[1] . $row[$link_data];
              }
            }

            if($link != "" && $link != " ") {
              echo "<td class='gridButtonTd'><a href='$link'><input class='btn btn-primary leftGridButton' type='button' value='$button_header'/></a></td>";
            } else {
              echo "<td class='gridButtonTd'><div class='btn btn-primary leftGridButton'>$button_header</div></td>";
            }
          }
        }

        for($i = 0; $i < count($data_array); $i++) {
          echo "<td class='gridData'>" . $row[$data_array[$i]] . "</td>";
        }

        if(!empty($rightButtons)) {
          foreach($rightButtons as $button_header => $button_link) {

            if(strpos($button_link, '**') !== false) {
              $explode = explode("**", $button_link);
              $link = $explode[0];
              $link_data = $explode[1];
            } else {
              $link = $button_link;
              $link_data = "";
            }

            if($link_data) {
              if(strpos($button_link, '?') !== false) {
                $explode = explode("?", $link);
                $link = $explode[0] . "?" . $explode[1] . $row[$link_data];
              }
            }

            if(strtolower($button_header) == 'disable' || strtolower($button_header) == 'delete') {
              $button_color = "btn btn-danger";
            } else {
              $button_color = "btn btn-success";
            }

            if($link != "" && $link != " ") {
              echo "<td class='gridButtonTd'><a href='$link'><input class='$button_color rightGridButton' type='button' value='$button_header'/></a></td>";
            } else {
              echo "<td class='gridButtonTd'><div class='$button_color rightGridButton'>$button_header</div></td>";
            }
          }
        }

        echo "</tr>";
      }

    echo "</table>";

  }

}
