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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}


/**
 * Class PluginServiciosServicioInjection
 */
class PluginServiciosServicioInjection extends PluginServiciosServicio
   implements PluginDatainjectionInjectionInterface {

   /**
    * @param null $classname
    *
    * @return mixed
    */
   static function getTable($classname = null) {

      $parenttype = get_parent_class();
      return $parenttype::getTable();

   }

   /**
    * @return bool
    */
   function isPrimaryType() {
      return true;
   }

   /**
    * @return array
    */
   function connectedTo() {
      return [];
   }

   /**
    * @param string $primary_type
    *
    * @return array
    */
   function getOptions($primary_type = '') {

 $tab = Search::getOptions(get_parent_class($this));

      //Specific to location
      $tab[6]['linkfield'] = 'locations_id';
      //$blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();
      //Remove some options because some fields cannot be imported
      $notimportable = array(13,17,30,80);
      $options['ignore_fields'] = $notimportable;
      $options['displaytype'] = array("dropdown"       => array(2,4,5,7,10,14),
                                      "user"           => array(9),
                                      "multiline_text" => array(16),
                                      "bool"           => array(15,18));

      $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

      return $tab;

   }

   /**
    * Standard method to delete an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    *
    * @param fields fields to add into glpi
    * @param options options used during creation
   **/
   function deleteObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->deleteObject();
      return $lib->getInjectionResults();
   }

   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    *
    * @param values fields to add into glpi
    * @param options options used during creation
    *
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }

}

?>