<?php
/*
 * @version $Id: HEADER 15930 2020-01-10 14:40:00Z JDMZ$
 -------------------------------------------------------------------------
 Servicios plugin for GLPI
 Copyright (C) 2020 by the CARM Development Team.

 https://github.com/calidadcarm/servicios
 -------------------------------------------------------------------------

 LICENSE
		
 This file is part of Servicios.

 Servicios is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Servicios is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Servicios. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

if (strpos($_SERVER['PHP_SELF'],"dropdownTypeservicios.php")) {
   include ('../../../inc/includes.php');
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

Session::checkCentralAccess();

// Make a select box
if (isset($_POST["serviciotype"])) {
   $used = array();

   // Clean used array
   if (isset($_POST['used']) && is_array($_POST['used']) && (count($_POST['used']) > 0)) {
      $query = "SELECT `id`
                FROM `glpi_plugin_servicios_servicios`
                WHERE `id` IN (".implode(',',$_POST['used']).")
                      AND `plugin_servicios_serviciotypes_id` = '".$_POST["serviciotype"]."'";

      foreach ($DB->request($query) AS $data) {
         $used[$data['id']] = $data['id'];
      }
   }

   Dropdown::show('PluginServiciosServicio',
                  array('name'      => $_POST['myname'],
                        'used'      => $used,
                        'width'     => '50%',
                        'entity'    => $_POST['entity'],
                        'rand'      => $_POST['rand'],
                        'condition' => "glpi_plugin_servicios_servicios.plugin_servicios_serviciotypes_id='".$_POST["serviciotype"]."'"));

}
?>