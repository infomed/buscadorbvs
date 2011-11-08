<?php
/**
 * Realiza labores de maquetacion y calculo de los elementos de paginacion
 * Distingue entre páginas a seleccionar y la página seleccionada, dando la
 * opcion de establecer representaciones distintas. El resto del código HTML,
 * anterior y posterior, corre a manos del programador.
 *  Ejemplo (código HTML):
 *   @code
 *   <div class="paginacion">Páginas: 
 *     <?php
 *     // Offset de 15 elementos, páginas con 10 elementos y un total de 85
 *     $paginacion =& new Paginacion(15, 10, 85);
 *     // Mostramos 1 2 3 4 5 6 7 8 9 cada numero representado con su patron
 *     $paginacion->render();
 *     ?>
 *   </div>
 *   @endcode
 *
 * En el caso de usar la paginación con elementos como XTemplates, se puede
 * usar el método getRender() que devuelve el código de paginación.
 *  Ejemplo:
 *   @code
 *     <?php
 *     // Offset de 15 elementos, páginas con 10 elementos y un total de 85
 *     $paginacion =& new Paginacion(15, 10, 85);
 *     // Recogemos la informacion
 *     $numeros = $paginacion->getRender();
 *     // Lo ponemos en la plantilla anteriormente cargada en $xtpl
 *     $xtpl->assign("paginacion", $numeros);
 *     ?>
 *   @endcode
 *
 *  LICENCE
 *  ========
 *    Copyright (c) 2005 Francisco Jose Naranjo [fran@navarparty.org]
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public License
 *    version 2.1 as published by the Free Software Foundation.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details at
 *    http://www.gnu.org/copyleft/lgpl.html
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @brief Maquetación y cálculo de paginaciones
 * @author Fran Naranjo (aka Tatai)
 * @date 2005-11
 * @version 0.3
 */
class Paginacion {
	
	/**
	 * Offset de elementos (elemento actual)
	 *
	 * @private
	 */
	var $_offset = null;
	
	/**
	 * Numero de elementos por pagina
	 *
	 * @private
	 */
	var $_nitems = null;
	
	/**
	 * Numero total de elementos
	 *
	 * @private
	 */
	var $_ntotal = null;

	/**
	 * Patron de diseño de un numero de pagina a seleccionar
	 *
	 * @private
	 */
	var $_numberPattern = null;

	/**
	 * Patron de diseño del numero de pagina seleccionada
	 *
	 * @private
	 */
	var $_numberSelectedPattern = null;

	/**
	 * Número máximo de páginas que se mostrarán
	 *
	 * @private
	 */
	var $_maxPages = null;

	/**
	 * Constructor
	 * Establece los patrones para cada número de página (NumberPattern) y para
	 * el número que indica la página actual (NumberSelectedPattern)
	 *
	 * @param $offset Elemento actual. Número entero donde 0 es el primer elemento
	 * @param $nitems Número de elementos por página
	 * @param $ntotal Número total de elementos disponibles
	 * @public
	 * @sa setOffset setNitems setNtotal setNumberPattern setNumberSelectedPattern
	 */
	function Paginacion ($offset = 0, $nitems = 0, $ntotal = 0)	{
		$this->setOffset($offset);
		$this->setNitems($nitems);
		$this->setNtotal($ntotal);

		// Calculamos la pagina actual con los valores introducidos
		$this->_updatePaginaActual();

		// Establecemos un patron por defecto para el numero
		$patron = " <a href=\"?offset={OFFSET}\">{NUMERO}</a> ";
		$this->setNumberPattern($patron);
		
		// Establecemos un patron por defecto para el numero seleccionado
		$patron = " <strong>{NUMERO}</strong> ";
		$this->setNumberSelectedPattern($patron);

		// Establecemos en 10 el número máximo de páginas
		$this->setMaxPaginas(10);
	}
	
	/**
	 * Establece el offset (elemento actual)
	 *
	 * @param $offset Elemento actual. Número entero donde 0 es el primer elemento
	 * @public
	 */
	function setOffset($offset)	{
		$this->_offset = $offset;
		
		// Calculamos la pagina actual con el nuevo valor introducido
		$this->_updatePaginaActual();
	}
		
	/**
	 * Establece el numero de elementos por pagina
	 *
	 * @param $nitems Número de elementos por página
	 * @public
	 */
	function setNitems($nitems)	{
		$this->_nitems = $nitems;
		
		// Calculamos la pagina actual con el nuevo valor introducido
		$this->_updatePaginaActual();
	}

	/**
	 * Establece el numero total de elementos disponibles
	 *
	 * @param $ntotal Número total de elementos disponibles
	 * @public
	 */
	function setNtotal($ntotal)	{
		$this->_ntotal = $ntotal;
		
		// Calculamos la pagina actual con el nuevo valor introducido
		$this->_updatePaginaActual();
	}
	
	/**
	 * Devuelve el offset (elemento actual)
	 *
	 * @private
	 * @return int offset (elemento actual)
	 */
	function _getOffset()	{
		return $this->_offset;
	}
		
	/**
	 * Devuelve el numero de elementos por pagina
	 *
	 * @private
	 * @return int numero de elementos por pagina
	 */
	function _getNitems()	{
		return $this->_nitems;
	}

	/**
	 * Devuelve el numero total de elementos disponibles
	 *
	 * @private
	 * @return int numero total de elementos disponibles
	 */
	function _getNtotal() {
		return $this->_ntotal;
	}

	/**
	 * Calcula la pagina en la que se encuentran con los valores dados
	 *
	 * @private
	 * @return int pagina que corresponde al offset
	 */
	function _calcPagina($offset, $nitems) {
		if($offset == 0 || $nitems == 0)
			return 1;
		else
			return ceil(($offset+1) / $nitems);
	}

	/**
	 * Actualiza la pagina actual la pagina actual en base al offset y al numero
	 * de elementos por pagina disponibles
	 *
	 * @private
	 */
	function _updatePaginaActual() {
		$this->_paginaActual = $this->_calcPagina($this->_getOffset(), $this->_getNitems());
	}
	
	/**
	 * Devuelve el numero de página actual en base al offset y al numero de
	 * elementos por pagina
	 *
	 * @public
	 * @return int numero actual de pagina
	 */
	function getPaginaActual() {
		return $this->_paginaActual;
	}
	
	/**
	 * Establece un patron para un numero
	 * Tiene que contener las cadenas:
	 *  {NUMERO}: el número que se pinta
	 *  {OFFSET}: offset que tiene ese numero
	 *
	 * @public
	 */
	function setNumberPattern($pattern)	{
		$this->_numberPattern = $pattern;
	}
	
	/**
	 * Establece un patron para un numero seleccionado
	 * Tiene que contener la cadena:
	 *  {NUMERO}: el número que se pinta
	 * Adicionalmente puede disponer de 	 
	 *  {OFFSET}: offset que tiene ese numero
	 *
	 * @public
	 */
	function setNumberSelectedPattern($pattern)	{
		$this->_numberSelectedPattern = $pattern;
	}
	
	/**
	 * Renderiza un numero con el offset dado y lo retorna
	 *
	 * @param $offset Elemento perteneciente a la pagina que quiere renderizarse
	 * @private
	 * @return string cadena renderizada del numero
	 */
	function _renderNumber($offset)	{
		$pagina = $this->_calcPagina($offset, $this->_getNitems());

		// Seleccionamos el patron adecuado
		if($pagina == $this->getPaginaActual())
			$patron = $this->_numberSelectedPattern;
		else
			$patron = $this->_numberPattern;
		
		$patron = preg_replace("/{NUMERO}/", $pagina, $patron);
		$patron = preg_replace("/{OFFSET}/", $offset, $patron);
		
		return $patron;
	}
	
	/**
	 * Renderiza el elemento completo de paginado y lo muestra en pantalla (print)
	 * <strong>Importante:</strong> el código generado no se devuelve, sino
	 * que se hace un print, con lo que la llamada a este método debe
	 * hacerse en el lugar donde debe aparecer la paginacion
	 *
	 * @public
	 */
	function render() {
		print $this->getRender();
	}

	/**
	 * Renderiza el elemento completo de paginado y lo retorna al final de la funcion
	 * <strong>Importante:</strong> el código generado se devuelve al final del
	 * proceso, al contrario que render() 
	 * 
	 * @public
	 * @retval string cadena a imprimir con los elementos del paginado
	 */
	function getRender() {
		// Cadena donde iremos guardando el paginado
		$salida = "";
		
		// Obtenemos el total del paginas (= la pagina del ultimo elemento)
		$totalPags = $this->getPaginas();
		//_calcPagina($this->_getNtotal(), $this->_getNitems());
	
		// Calculamos la mitad de maxPages
		$mitad = floor($this->getMaxPaginas() / 2);
		// Solo una linea de paginacion
		if($totalPags <= $this->getMaxPaginas())
			$salida .= $this->_renderFromPageToPage(1, $totalPags);
		// Tenemos mas de maxPages
		else {
			// Nos encontramos en las primeras paginas
			if($this->getPaginaActual() <= $mitad)
				$salida .= $this->_renderFromPageToPage(1, $this->getMaxPaginas());
			// Nos encontramos en las ultimas paginas
			else if($this->getPaginaActual() > ($totalPags-$mitad))
				$salida .= $this->_renderFromPageToPage($totalPags-($this->getMaxPaginas()-1), $totalPags);
			// Estamos en medio
			else {
				// Si getMaxPaginas es par
				if(($this->getMaxPaginas() % 2) == 0)
					$salida .= $this->_renderFromPageToPage($this->getPaginaActual()-$mitad, $this->getPaginaActual()+$mitad-1);
				else
					$salida .= $this->_renderFromPageToPage($this->getPaginaActual()-$mitad, $this->getPaginaActual()+$mitad);
			}
		}
		
		return $salida;
	}

	/**
	 * Renderiza el elemento indicado y muestra el texto que se le indique en vez
	 * de el número correspondiente. El offset indica la página respecto a la
	 * actual
	 * <strong>Importante:</strong> el código generado se devuelve al final del
	 * proceso, al contrario que renderSingle() 
	 * 
	 * @param $pag_offset Página a mostrar: +1=Siguiente, -1=Anterior
	 * @param $texto Texto a mostrar
	 * @public
	 * @retval string cadena a imprimir con el elemento requerido
	 */
	function getRenderSingle($pag_offset, $texto) {
		$pagina = $this->getPaginaActual() + (1 * $pag_offset);
		$offset = ($pagina - 1) * $this->_getNitems();

		// Seleccionamos el patron adecuado
		if($pagina == $this->getPaginaActual())
			$patron = $this->_numberSelectedPattern;
		else
			$patron = $this->_numberPattern;
		
		$patron = preg_replace("/{NUMERO}/", $texto, $patron);
		$patron = preg_replace("/{OFFSET}/", $offset, $patron);
		
		return $patron;
	}
	
	/**
	 * Renderiza el elemento indicado y muestra el texto que se le indique en vez
	 * de el número correspondiente. El offset indica la página respecto a la
	 * actual
	 * <strong>Importante:</strong> el código generado no se devuelve, sino
	 * que se imprime, al contrario que getRenderSingle() 
	 * 
	 * @param $pag_offset Página a mostrar: +1=Siguiente, -1=Anterior
	 * @param $texto Texto a mostrar
	 * @public
	 * @retval string cadena a imprimir con el elemento requerido
	 */
	function renderSingle($pag_offset, $texto) {
		print $this->getRenderSingle($pag_offset, $texto);
	}
	
	/**
	 * Renderiza el rango de páginas indicadas (inclusive)
	 *
	 * @param $inicio Página inicial (int) donde 1 es la primera página
	 * @param $fin Página final (int) que se renderizará
	 * @private
	 * @return string render de las paginas
	 */
	function _renderFromPageToPage($inicio, $fin) {
		$cadena = "";

		for($i=$inicio;$i<=$fin;$i++) {
			$cadena .= $this->_renderNumber(($i-1)*$this->_getNitems());
		}

		return $cadena;
	}
	
	/**
	 * Calcula el número total de paginas en funcion de los valores que tiene la clase
	 *
	 * @public
	 * @return int numero total de paginas
	 */
	function getPaginas() {
		return $this->_calcPagina($this->_getNtotal()-1, $this->_getNitems());
	}
	
	/**
	 * Establece el número máximo de páginas a mostrar
	 * El valor debe ser igual o superior a 5
	 *
	 * @public
	 */
	function setMaxPaginas($pages) {
		if($pages >= 5)
			$this->_maxPages = $pages;
		else
			$this->_maxPages = 5;
	}

	/**
	 * Retorna el número máximo de páginas a mostrar
	 *
	 * @public
	 * @return int numero máximo de páginas a mostrar
	 */
	function getMaxPaginas() {
		return $this->_maxPages;
	}
	
}
?>
