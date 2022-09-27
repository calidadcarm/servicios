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

 
class PluginServiciosMenu extends CommonGLPI {
   public static $rightname = 'plugin_servicios';

   public static function getMenuName() {
      return _n('Servicio', 'Servicios', 2, 'servicios');
   }

   public static function getMenuContent() {
      global $CFG_GLPI;

      $menu                                           = array();
      $menu['title']                                  = self::getMenuName();
      $menu['page']                                   = "/plugins/servicios/front/servicio.php";
      $menu['links']['search']                        = PluginServiciosServicio::getSearchURL(false);
      if (PluginServiciosServicio::canCreate()) {
         $menu['links']['add']                        = PluginServiciosServicio::getFormURL(false);
      }

      return $menu;
   }

   public static function removeRightsFromSession() {
      if (isset($_SESSION['glpimenu']['tools']['types']['PluginServiciosMenu'])) {
         unset($_SESSION['glpimenu']['tools']['types']['PluginServiciosMenu']); 
      }
      if (isset($_SESSION['glpimenu']['tools']['content']['pluginserviciosmenu'])) {
         unset($_SESSION['glpimenu']['tools']['content']['pluginserviciosmenu']); 
      }
   }
}