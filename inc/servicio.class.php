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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginServiciosServicio extends CommonDBTM {

   public $dohistory=true;
   static $rightname                   = "plugin_servicios";
   protected $usenotepadrights         = true;
   
   static $types = array('Computer', 'Monitor', 'NetworkEquipment', 'Peripheral', 'Phone',
                            'Printer', 'Software', 'Entity');

   public static function getTypeName($nb=0) {

      return _n('Servicio', 'Servicios', $nb, 'servicios');
   }

   static function getIcon() {
      return "fas fa-fire";
   }     

   //clean if servicios are deleted
   public function cleanDBonPurge() {

      $temp = new PluginServiciosServicio_Item();
      $temp->deleteByCriteria(array('plugin_servicios_servicios_id' => $this->fields['id']));
   }

   public function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType()=='Supplier') {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry(self::getTypeName(2), self::countForItem($item));
         }
         return self::getTypeName(2);
      }
      return '';
   }


   public static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      global $CFG_GLPI;

      if ($item->getType()=='Supplier') {
         PluginServiciosServicio_Item::showForSupplier($item);
      }
      return true;
   }
   
   public static function countForItem(CommonDBTM $item) {
	   
$criteria = [
"suppliers_id" => $item->getID(),
];		   

      return countElementsInTable('glpi_plugin_servicios_servicios',
                                  $criteria);
   }
   
   public function rawSearchOptions () {

      $tab                       = array();
	  
	  $tab[] = [
'id' => 'common',
'name' => self::getTypeName(2),
];
    
$tab[] = [
'id' => 1,
'table' => self::getTable(),
'field' => 'id',
'name' => __("ID"),
'itemlink_type' => self::getType(),
'massiveaction' => false, // implicit field is id
'injectable' => false,
'datatype' => 'number', 
];

$tab[] = [
'id' => 2,
'table' => self::getTable(),
'field' => 'name',
'name' => __("Name"),
'itemlink_type' => self::getType(),
'datatype' => 'itemlink', 
];

$tab[] = [
'id' => 3,
'table' => 'glpi_locations',
'field' => 'completename',
'name' => __("Location"),
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
];

$tab[] = [
'id' => 4,
'table' => 'glpi_users',
'field' => 'name',
'name' => __('Technician in charge of the hardware'),
'linkfield' => 'users_id_tech',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'right' => 'interface', 
];

$tab[] = [
'id' => 5,
'table' => 'glpi_groups',
'field' => 'name',
'name' => __('Group in charge of the hardware'),
'linkfield' => 'groups_id_tech',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'condition' => ['is_assign'=>1], 
];

$tab[] = [
'id' => 6,
'table' => 'glpi_plugin_servicios_servicios_items',
'field' => 'items_id',
'nosearch' => true,
'massiveaction' => false,
'name' => _n('Associated item' , 'Associated items', 2),
'forcegroupby' => true,
'joinparams' => [
				'jointype'           => 'child',
                ]
];
	
$tab[] = [
'id' => 207,
'table' => $this->getTable(),
'field' => 'is_recursive',
'name' => __('Child entities'),
'datatype' => 'bool', 
];	

$tab[] = [
'id' => 208,
'table' => $this->getTable(),
'field' => 'comment',
'name' => __('Comments'),
'datatype' => 'text', 
];	
	
$tab[] = [
'id' => 209,
'table' => $this->getTable(),
'field' => 'date_mod',
'massiveaction' => false,
'name' => __('Last update'),
'datatype' => 'datetime', 
];		
	

$tab[] = [
'id' => 210,
'table' => $this->getTable(),
'field' => 'is_helpdesk_visible',
'name' => __('Associable to a ticket'),
'datatype' => 'bool', 
'searchtype' => 'equals',
];	
	
	
	
$tab[] = [
'id' => 211,
'table' => 'glpi_entities',
'field' => 'completename',
'name' => __('Entity'),
'datatype' => 'dropdown', 
];	

/*
$tab[] = [
'id' => 212,
'table' => $this->getTable(),
'field' => 'is_helpdesk_visible',
'name' => __('Associable to a ticket'),
'datatype' => 'bool',
'searchtype' => 'equals', 
];	
*/

$tab[] = [
'id' => 213,
'table' => 'glpi_plugin_servicios_orientados',
'field' => 'name',
'name' => __('Orientado a'),
'datatype' => 'dropdown', 
'injectable' => true,  
'searchtype' => 'equals',
];	
	


$tab[] = [
'id' => 214,
'table' => 'glpi_states',
'field' => 'name',
'name' => __('Estado'),
'linkfield' => 'states_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'injectable' => true, 
];

		
$tab[] = [
'id' => 215,
'table' => 'glpi_users',
'field' => 'name',
'name' => __('Responsable del servicio'),
'linkfield' => 'responsable_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'injectable' => true, 
];		
	
	
$tab[] = [
'id' => 216,
'table' => 'glpi_users',
'field' => 'name',
'name' => __('Responsable del negocio'),
'linkfield' => 'users_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'injectable' => true, 
];		

$tab[] = [
'id' => 217,
'table' => 'glpi_groups',
'field' => 'name',
'name' => __('Grupo usuario'),
'linkfield' => 'groups_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'injectable' => true, 
];	


$tab[] = [
'id' => 218,
'table' => $this->getTable(),
'field' => 'titulo',
'name' => __('Título'),
'displaytype' => 'text',
'checktype' => self::getType(),
'datatype' => 'text', 
];	

$tab[] = [
'id' => 219,
'table' => $this->getTable(),
'field' => 'descripcion',
'name' => __('Descripcion'),
'displaytype' => 'multiline_text',
'checktype' => self::getType(),
'datatype' => 'text', 
'htmltext'  => true,
];	


$tab[] = [
'id' => 220,
'table' => $this->getTable(),
'field' => 'garantia',
'name' => __('Garantía'),
'displaytype' => 'multiline_text',
'checktype' => self::getType(),
'datatype' => 'text', 
'htmltext'  => true,
];


$tab[] = [
'id' => 221,
'table' => 'glpi_calendars',
'field' => 'name',
'name' => __('Calendario disponibilidad'),
'datatype' => 'dropdown',
'linkfield' => 'cal_id', 
'injectable' => true,  
'searchtype' => 'equals',
];	


$tab[] = [
'id' => 222,
'table' => 'glpi_plugin_servicios_criticidads',
'field' => 'name',
'name' => __('Criticidad'),
'datatype' => 'dropdown',
'linkfield' => 'plugin_servicios_criticidads_id', 
'injectable' => true,  
'searchtype' => 'equals',
];


$tab[] = [
'id' => 223,
'table' => $this->getTable(),
'field' => 'acronimosi',
'name' => __('Acronimo SI'),
'displaytype' => 'text',
'checktype' => self::getType(),
'datatype' => 'text', 
];

$tab[] = [
'id' => 224,
'table' => 'glpi_users',
'field' => 'name',
'name' => __('Responsable de seguridad'),
'linkfield' => 'responsableseguridad_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'right' => 'interface', 
];

$tab[] = [
'id' => 225,
'table' => $this->getTable(),
'field' => 'nusuarios',
'name' => __('Nº de usuarios'),
'displaytype' => 'text',
'checktype' => self::getType(),
'datatype' => 'text', 
];

$tab[] = [
'id' => 226,
'table' => $this->getTable(),
'field' => 'is_afectadoens',
'name' => __('Afectado por ENS'),
'datatype' => 'bool', 
'searchtype' => 'equals',
];	


$tab[] = [
'id' => 227,
'table' => 'glpi_plugin_servicios_ensnivels',
'field' => 'name',
'name' => __('Nivel ENS'),
'linkfield' => 'plugin_servicios_ensnivels_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'injectable' => true,   
'searchtype' => 'equals',
];

$tab[] = [
'id' => 228,
'table' => $this->getTable(),
'field' => 'ens_estado_implantacion',
'name' => __('Estado de implantacion del ENS'),
'itemlink_type' => self::getType(),
'datatype' => 'number', 
'min' => 1,   
'max' => 5,
'searchtype' => 'equals',
];


$tab[] = [
'id' => 229,
'table' => 'glpi_plugin_servicios_autentications',
'field' => 'name',
'name' =>__('Autenticación de personal interno'),
'linkfield' => 'autenticationinterno_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'right' => 'interface',   
'searchtype' => 'equals',
];


$tab[] = [
'id' => 230,
'table' => 'glpi_plugin_servicios_autentications',
'field' => 'name',
'name' =>__('Autenticacion de personal externo'),
'linkfield' => 'autenticationexterno_id',
'itemlink_type' => self::getType(),
'datatype' => 'dropdown', 
'right' => 'interface',   
'searchtype' => 'equals',
];
	   
      return $tab;
   }


   //define header form
   public function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginServiciosServicio_Item', $ong, $options);
      $this->addStandardTab('Ticket', $ong, $options);
      $this->addStandardTab('Item_Problem', $ong, $options);
	  $this->addStandardTab('KnowbaseItem_Item', $ong, $options); //[CRI] JMZ18G Añadir TAB Base de conocimiento
      $this->addStandardTab('Contract_Item', $ong, $options);
      $this->addStandardTab('Document_Item', $ong, $options);
      $this->addStandardTab('Notepad', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }


   /**
    * Return the SQL command to retrieve linked object
    *
    * @return a SQL command which return a set of (itemtype, items_id)
   **/
   public function getSelectLinkedItem () {

      return "SELECT `itemtype`, `items_id`
              FROM `glpi_plugin_servicios_servicios_items`
              WHERE `plugin_servicios_servicios_id`='" . $this->fields['id']."'";
   }


   public function showForm($ID, $options=array()) {

   $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      //name of servicios
      echo "<td>".__('Name')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {
      echo Html::input('name', ['value' => $this->fields['name']]);
	  }  else   {
		echo $this->fields['acronimosi'];
	  }	  
      echo "</td>";
      //version of servicios
      echo "<td>".__('Estado')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {	  
         Dropdown::show('State', array('value'  => $this->fields["states_id"],
                                          'entity' => $this->fields["entities_id"]));
      } else {
         echo Dropdown::getDropdownName("glpi_states",$this->fields["states_id"]);
      }											  
      echo "</td>";
      echo "</tr>";
	  // [INICIO] Añadido CRI.2.0
      echo "<tr class='tab_bg_1'>";
      //estado
      echo "<td>".__('Location')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {	 	  
      Dropdown::show('Location', array('value'  => $this->fields["locations_id"],
                                       'entity' => $this->fields["entities_id"]));
      } else {
         echo Dropdown::getDropdownName("glpi_locations",$this->fields["locations_id"]);
      }										   
      echo "</td>";
      //orientado a
      echo "<td>".__('Orientado a')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {	  	  
         Dropdown::show('PluginServiciosOrientado',
                     array('value' => $this->fields["plugin_servicios_orientados_id"]));
      } else {
         echo Dropdown::getDropdownName("glpi_plugin_servicios_orientados",$this->fields["plugin_servicios_orientados_id"]);
      }						 
      echo "</td>";
      echo "</tr>";	  
	  // [FIN]  Añadido CRI.2.0

      echo "<tr class='tab_bg_1'>";
      //location of servicios
      echo "<td>".__('Technician in charge of the hardware')."</td><td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {	 	  
      User::dropdown(array('name' => "users_id_tech",
                           'value'  => $this->fields["users_id_tech"],
                           'entity' => $this->fields["entities_id"],
                           'right'  => 'interface'));
      } else {
         echo Dropdown::getDropdownName("glpi_users", $this->fields["users_id_tech"]);		 
      }						   
      echo "</td>";
      //language of servicios
      echo "<td>".__('Group in charge of the hardware')."</td><td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
      Dropdown::show('Group', array('name' => "groups_id_tech",
                                    'value'  => $this->fields["groups_id_tech"],
                                    'entity' => $this->fields["entities_id"],
                                    'condition' => ['is_assign' => 1]));
      } else {
         echo Dropdown::getDropdownName("glpi_groups", $this->fields["groups_id_tech"]);		 
      }									
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //users
      echo "<td>".__('Responsable del servicio')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
		  User::dropdown(array('name'   => 'responsable_id',
							   'value'  => $this->fields["responsable_id"],
							   'right'  => 'all',
							   'entity' => $this->fields["entities_id"]));	
	  } else {
         echo Dropdown::getDropdownName("glpi_users", $this->fields["responsable_id"]);		 
      }	
      echo "</td>";
      //Grupo of servicios
      echo "<td>".__('Grupo usuario')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
         Dropdown::show('Group', array('name'      => 'groups_id',
                                       'value'     => $this->fields['groups_id'],
                                       'entity'    => $this->fields['entities_id']));
      } else {
         echo Dropdown::getDropdownName("glpi_groups", $this->fields["groups_id"]);		 
      }										   
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //groups
      echo "<td>".__('Responsable del negocio')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
         User::dropdown(array('name'   => 'users_id',
                              'value'  => $this->fields['users_id'],
                              'right'  => 'all',
                              'entity' => $this->fields['entities_id']));
	  } else {
         echo Dropdown::getDropdownName("glpi_users", $this->fields["users_id"]);		 
      }	
      echo "</td>";

      //is_helpdesk_visible
      echo "<td>" . __('Associable to a ticket') . "</td><td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
		//Dropdown::showYesNo('is_helpdesk_visible', $this->fields['is_helpdesk_visible']);
		
				$rand = mt_rand();
				       
         echo "<span class='switch pager_controls'>
            <label for='is_helpdesk_visiblewitch$rand' title='".__('Mostrar avisos p&uacute;blicos')."'>
               <input type='hidden' name='is_helpdesk_visible' value='0'>
                              <input type='checkbox' id='is_helpdesk_visiblewitch$rand' name='is_helpdesk_visible' value='1'".
                     ($this->fields['is_helpdesk_visible']
                        ? "checked='checked'"
                        : "")."
               >
               <span class='lever'></span>
            </label>
         </span>";			
		
	  } else {
          echo Dropdown::getYesNo($this->fields['is_helpdesk_visible']);			 
      }			
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'><th style='height:15px; border-top: 12px solid white; border-bottom: 10px solid white;' colspan = '4'></th></tr>";
	  
      //backoffice of servicios
      /*echo "<td></td>";
      echo "<td></td>";*/

     /* echo "<td class='center' colspan = '4'>";
      printf(__('Last update on %s'), Html::convDateTime($this->fields["date_mod"]));
      echo "</td>";
      echo "</tr>";*/

	  //[INICIO] CRI 2.0 
	  // titulo
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Titulo')."</td>";
      echo "<td colspan='3'>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  		
	   //Html::autocompletionTextField($this, 'titulo', array('value' => $this->fields['titulo'], 'option' => 'style="height:25px; width:99%"'));
      echo Html::input('titulo', ['value' => $this->fields['titulo'], 'size' => '124']);
	  } else  {
		echo $this->fields['titulo'];
	  }		
      echo "</tr>";
	  // descripcion
     echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Descripcion')."</td>";
      echo "<td class='top center' colspan='3'>";
	  
	  if (isset($rand_text))
	  {
	  echo "<div id='descripcion$rand_text'>";
	  }
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {	
	  
      Html::textarea(['name'              => 'descripcion',
                      'value'             => $this->fields["descripcion"],
                      'enable_fileupload' => false,
                      'enable_richtext'   => true,                      
                      'cols'              => 90,
                      'rows'              => 20]);	

	  } else  {		 		 
		echo $this->fields['descripcion'];
	  }			
      echo "</tr>";
	  // garantia
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Garantia')."</td>";
      echo "<td class='top center' colspan='3'>";
	  $rand = mt_rand();
      
	  if (isset($rand_text))
	  {
	  echo "<div id='garantia$rand_text'>";
	  }
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
		
      Html::textarea(['name'              => 'garantia',
                      'value'             => $this->fields["garantia"],
                      'enable_fileupload' => false,
                      'enable_richtext'   => true,
                      'cols'              => 90,
                      'rows'              => 20]);			
	  } else  {
		echo $this->fields['garantia'];
	  }			
	  echo "</div>";	  
      echo "</tr>";	  
	  //[FINAL] CRI 2.0 
	  
      echo "<tr class='tab_bg_1'><th style='height:15px; border-top: 10px solid white; border-bottom: 12px solid white;' colspan = '4'></th></tr>";	  
	  
	  // [INICIO] Añadido CRI.2.0
      echo "<tr class='tab_bg_1'>";
      //Calendario disponibilidad
      echo "<td>".__('Calendario disponibilidad')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
			Dropdown::show('Calendar', array('value'  => $this->fields["cal_id"], 'name'   => 'cal_id'));
	  } else {
         echo Dropdown::getDropdownName("glpi_calendars", $this->fields["cal_id"]);		 
      }				
      echo "</td>";
      //criticidad a
      echo "<td>".__('Criticidad')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_general_fields", "1")) {		  
         Dropdown::show('PluginServiciosCriticidad',
                     array('value' => $this->fields["plugin_servicios_criticidads_id"]));
	  } else {
         echo Dropdown::getDropdownName("glpi_plugin_servicios_criticidads", $this->fields["plugin_servicios_criticidads_id"]);		 
      }						 
      echo "</td>";
      echo "</tr>";	  
	  // [FIN]  Añadido CRI.2.0	 
	  
	  /*-------------
	  CRI 2.0 Nuevos cambios 
	  ------------------ */
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Acronimo SI')."</td><td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) {
		echo "<input type='text' maxlength='10' size='10' name='acronimosi' value= '".$this->fields['acronimosi']."'>";
	  } 
	  else
	  {
		echo $this->fields['acronimosi'];
	  }
      echo "</td>";
      echo "<td>".__('Responsable de Seguridad')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) { 
		  User::dropdown(array('name'   => 'responsableseguridad_id',
							   'value'  => $this->fields["responsableseguridad_id"],
							   'right'  => 'all',
							   'entity' => $this->fields["entities_id"]));	
	  }
	  else
	  {
		 echo Dropdown::getDropdownName("glpi_users",$this->fields["responsableseguridad_id"]);
	  }
      echo "</td>";
      echo "</tr>";	  
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Num. de usuarios')."</td><td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) { 
		echo "<input type='text' maxlength='10' size='10' name='nusuarios' value= '".$this->fields['nusuarios']."'>";
	  }
	  else
	  {
		echo $this->fields['nusuarios'];
	  }	  
      echo "</td>";

      echo "<td>".__('Afectado por ENS')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) { 	  
		//	Dropdown::showYesNo('is_afectadoens', $this->fields['is_afectadoens']); 
			
				$rand = mt_rand();
				       
         echo "<span class='switch pager_controls'>
            <label for='is_afectadoenswitch$rand' title='".__('Mostrar avisos p&uacute;blicos')."'>
               <input type='hidden' name='is_afectadoens' value='0'>
                              <input type='checkbox' id='is_afectadoenswitch$rand' name='is_afectadoens' value='1'".
                     ($this->fields['is_afectadoens']
                        ? "checked='checked'"
                        : "")."
               >
               <span class='lever'></span>
            </label>
         </span>";				
			
	  }
	  else
	  {
		  echo Dropdown::getYesNo($this->fields['is_afectadoens']);
	  }			
      echo "</td>";
      echo "</tr>";	 	  

      echo "</tr>";	  
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Nivel ENS')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) { 	  
	           Dropdown::show('PluginServiciosEnsnivel',
                     array('name'   => 'plugin_servicios_ensnivels_id',
						 'value' => $this->fields["plugin_servicios_ensnivels_id"]));
	  }
	  else
	  {
		  echo Dropdown::getDropdownName("glpi_plugin_servicios_ensnivels",$this->fields["plugin_servicios_ensnivels_id"]);
	  }						 
      echo "</td>";

      echo "<td>".__('Estado de implantacion de ENS')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) {
         Dropdown::showNumber('ens_estado_implantacion', array('value' => $this->fields['ens_estado_implantacion'],
                                                     'min'   => 1,
                                                     'max'   => 5));
	  }
	  else
	  {
		  echo $this->fields['ens_estado_implantacion'];
	  }
      echo "</td>";
      echo "</tr>";	 

      echo "</tr>";	  
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Autenticacion de personal interno')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) {
	           Dropdown::show('PluginServiciosAutentication',
                     array('name'   => 'autenticationinterno_id',
							'value' => $this->fields["autenticationinterno_id"]));
	  }
	  else
	  {
		  echo Dropdown::getDropdownName("glpi_plugin_servicios_autentications",$this->fields["autenticationinterno_id"]);
	  }							
      echo "</td>";

      echo "<td>".__('Autenticacion de personal externo')."</td>";
      echo "<td>";
	  if (Session::haveRight("plugin_servicios_security_fields", "1")) {
	           Dropdown::show('PluginServiciosAutentication',
                     array('name'   => 'autenticationexterno_id',
							'value' => $this->fields["autenticationexterno_id"]));
	  }
	  else
	  {
		 echo Dropdown::getDropdownName("glpi_plugin_servicios_autentications",$this->fields["autenticationexterno_id"]);
	  }								
      echo "</td>";
      echo "</tr>";		  
	  

      $this->showFormButtons($options);
      
      //return true;
   }

   
   /**
    * Make a select box for link servicios
    *
    * Parameters which could be used in options array :
    *    - name : string / name of the select (default is documents_id)
    *    - entity : integer or array / restrict to a defined entity or array of entities
    *                   (default -1 : no restriction)
    *    - used : array / Already used items ID: not to display in dropdown (default empty)
    *
    * @param $options array of possible options
    *
    * @return nothing (print out an HTML select box)
   **/
   public static function dropdown_servicio($options=array()) {
      global $DB, $CFG_GLPI;


      $p['name']    = 'plugin_servicios_servicios_id';
      $p['entity']  = '';
      $p['used']    = array();
      $p['display'] = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $where = " WHERE `glpi_plugin_servicios_servicios`.`is_deleted` = '0' ".
                       getEntitiesRestrictRequest("AND", "glpi_plugin_servicios_servicios", '', $p['entity'], true);

      if (count($p['used'])) {
         $where .= " AND `id` NOT IN (0, ".implode(",",$p['used']).")";
      }

      $query = "SELECT *
                FROM `glpi_plugin_servicios_serviciotypes`
                WHERE `id` IN (SELECT DISTINCT `plugin_servicios_serviciotypes_id`
                               FROM `glpi_plugin_servicios_servicios`
                             $where)
                ORDER BY `name`";
      $result = $DB->query($query);

      $values = array(0 => Dropdown::EMPTY_VALUE);

      while ($data = $DB->fetchAssoc($result)) {
         $values[$data['id']] = $data['name'];
      }
      $rand = mt_rand();
      $out  = Dropdown::showFromArray('_serviciotype', $values, array('width'   => '30%',
                                                                'rand'    => $rand,
                                                                'display' => false));
      $field_id = Html::cleanId("dropdown__serviciotype$rand");

      $params   = array('serviciotype' => '__VALUE__',
                        'entity' => $p['entity'],
                        'rand'   => $rand,
                        'myname' => $p['name'],
                        'used'   => $p['used']);

      $out .= Ajax::updateItemOnSelectEvent($field_id,"show_".$p['name'].$rand,
                                            $CFG_GLPI["root_doc"]."/plugins/servicios/ajax/dropdownTypeservicios.php",
                                            $params, false);
      $out .= "<span id='show_".$p['name']."$rand'>";
      $out .= "</span>\n";

      $params['serviciotype'] = 0;
      $out .= Ajax::updateItem("show_".$p['name'].$rand,
                               $CFG_GLPI["root_doc"]. "/plugins/servicios/ajax/dropdownTypeservicios.php",
                               $params, false);
      if ($p['display']) {
         echo $out;
         return $rand;
      }
      return $out;
   }


   /**
    * Show for PDF an servicios
    *
    * @param $pdf object for the output
    * @param $ID of the servicios
   **/
   public function show_PDF($pdf) {
      global $LANG, $DB;

      $pdf->setColumnsSize(50,50);
      $col1 = '<b>'.__('ID').' '.$this->fields['id'].'</b>';
      if (isset($this->fields["date_mod"])) {
         $col2 = printf(__('Last update on %s'), Html::convDateTime($this->fields["date_mod"]));
      } else {
         $col2 = '';
      }
      $pdf->displayTitle($col1, $col2);

      $pdf->displayLine(
         '<b><i>'.__('Name').':</i></b> '.$this->fields['name'],
         '<b><i>'.PluginServiciosServicioType::getTypeName(1).' :</i></b> '.
               Html::clean(Dropdown::getDropdownName('glpi_plugin_servicios_serviciotypes',
                                                    $this->fields['plugin_servicios_serviciotypes_id'])));
      $pdf->displayLine(
         '<b><i>'.__('Technician in charge of the hardware').':</i></b> '.getUserName($this->fields['users_id_tech']),
         '<b><i>'.__('Group in charge of the hardware').':</i></b> '.Html::clean(Dropdown::getDropdownName('glpi_groups',
                                                               $this->fields['groups_id_tech'])));
      $pdf->displayLine(
         '<b><i>'.__('Location').':</i></b> '.
               Html::clean(Dropdown::getDropdownName('glpi_locations', $this->fields['locations_id'])));



      $pdf->setColumnsSize(100);

      $pdf->displayText('<b><i>'.__('Comments').':</i></b>', $this->fields['comment']);

      $pdf->displaySpace();
   }
   
   /**
    * For other plugins, add a type to the linkable types
    *
    * @since version 1.3.0
    *
    * @param $type string class name
   **/
   public static function registerType($type) {
      if (!in_array($type, self::$types)) {
         self::$types[] = $type;
      }
   }


   /**
    * Type than could be linked to a Rack
    *
    * @param $all boolean, all type, or only allowed ones
    *
    * @return array of types
   **/
   public static function getTypes($all=false) {

      if ($all) {
         return self::$types;
      }

      // Only allowed types
      $types = self::$types;

      foreach ($types as $key => $type) {
         if (!class_exists($type)) {
            continue;
         }

         $item = new $type();
         if (!$item->canView()) {
            unset($types[$key]);
         }
      }
      return $types;
   }
   
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::getSpecificMassiveActions()
   **/
   public function getSpecificMassiveActions($checkitem=NULL) {
      $isadmin = static::canUpdate();
      $actions = parent::getSpecificMassiveActions($checkitem);

      if ($_SESSION['glpiactiveprofile']['interface'] == 'central') {
         if ($isadmin) {
            $actions['PluginServiciosServicio'.MassiveAction::CLASS_ACTION_SEPARATOR.'install']    = _x('button', 'Associate');
            $actions['PluginServiciosServicio'.MassiveAction::CLASS_ACTION_SEPARATOR.'uninstall'] = _x('button', 'Dissociate');

            if (Session::haveRight('transfer', READ)
                     && Session::isMultiEntitiesMode()
            ) {
               $actions['PluginServiciosServicio'.MassiveAction::CLASS_ACTION_SEPARATOR.'transfer'] = __('Transfer');
            }
         }
      }
      return $actions;
   }
   
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   public static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {
         case 'plugin_servicios_add_item':
            self::dropdown_servicio(array());
            echo "&nbsp;".
                 Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
         case "install" :
            Dropdown::showAllItems("item_item", 0, 0, -1, self::getTypes(true), 
                                   false, false, 'typeitem');
            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
         case "uninstall" :
            Dropdown::showAllItems("item_item", 0, 0, -1, self::getTypes(true), 
                                   false, false, 'typeitem');
            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
         case "transfer" :
            Dropdown::show('Entity');
            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
    }
      return parent::showMassiveActionsSubForm($ma);
   }
   
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   public static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {
      global $DB;
      
      $web_item = new PluginServiciosServicio_Item();
      
      switch ($ma->getAction()) {
         case "plugin_servicios_add_item":
            $input = $ma->getInput();
            foreach ($ids as $id) {
               $input = array('plugin_servicios_servicios_id' => $input['plugin_servicios_servicios_id'],
                                 'items_id'      => $id,
                                 'itemtype'      => $item->getType());
               if ($web_item->can(-1,UPDATE,$input)) {
                  if ($web_item->add($input)) {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
                  }
               } else {
                  $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
               }
            }

            return;
         case "transfer" :
            $input = $ma->getInput();
            if ($item->getType() == 'PluginServiciosServicio') {
            foreach ($ids as $key) {
                  $item->getFromDB($key);
                  $type = PluginServiciosServicioType::transfer($item->fields["plugin_servicios_serviciotypes_id"], $input['entities_id']);
                  if ($type > 0) {
                     $values["id"] = $key;
                     $values["plugin_servicios_serviciotypes_id"] = $type;
                     $item->update($values);
                  }

                  unset($values);
                  $values["id"] = $key;
                  $values["entities_id"] = $input['entities_id'];

                  if ($item->update($values)) {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               }
            }
            return;

         case 'install' :
            $input = $ma->getInput();
            foreach ($ids as $key) {
               if ($item->can($key, UPDATE)) {
                  $values = array('plugin_servicios_servicios_id' => $key,
                                 'items_id'      => $input["item_item"],
                                 'itemtype'      => $input['typeitem']);
                  if ($web_item->add($values)) {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               } else {
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_NORIGHT);
                  $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
               }
            }
            return;
            
         case 'uninstall':
            $input = $ma->getInput();
            foreach ($ids as $key) {
               if ($val == 1) {
                  if ($web_item->deleteItemByserviciosAndItem($key,$input['item_item'],$input['typeitem'])) {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               }
            }
            return;
      }
      parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
   }
}
?>