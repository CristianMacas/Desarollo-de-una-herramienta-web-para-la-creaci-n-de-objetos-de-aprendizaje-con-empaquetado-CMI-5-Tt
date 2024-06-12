<?php

/**
 * Este script se utiliza para comparar dos objetos de diagrama de GoJS.
 * Los objetos son cadenas de texto con un JSON.
 * Uno de los objetos se toma como plantilla y el otro es el que se somete 
 * a prueba para ver si es similar a la plantilla. 
 * Lo que se hace es comparar la estructura lógica y se ignora la representación visual.
 */

function check_uml_properties($result, $className, $templateUmlPropertiesList, $testUmlPropertiesList){
    $prefix = 'En la clase "'.$className.'" ';
    $deltaCountProperties = sizeof($testUmlPropertiesList) - sizeof($templateUmlPropertiesList);
    if ($deltaCountProperties < 0){
        $result[] = $prefix."faltan propiedades. Cantidad faltante: ".abs($deltaCountProperties);
    }elseif ($deltaCountProperties > 0){
        $result[] = $prefix."sobran propiedades. Cantidad sobrante: ".abs($deltaCountProperties);
    }
    foreach($templateUmlPropertiesList as $template){
        $found = array_filter($testUmlPropertiesList, function($item) use ($template){
            if (! property_exists($item, "name")) return(False);
            return($item->name == $template->name);
        });
        if (sizeof($found) > 1){
            $result[] = $prefix.'existen propiedades con el mismo nombre "'.$template->name.'"';
        }elseif (sizeof($found) <= 0){
            $result[] = $prefix.'no se encuentra la propiedad "'.$template->name.'"';
        }else{
            if (reset($found)->type != $template->type){
                $result[] = $prefix.'la propiedad "'.$template->name.'" debe ser tipo "'.$template->type.'"';
            }
            if (reset($found)->visibility != $template->visibility){
                $result[] = $prefix.'la visibilidad de la propiedad "'.$template->name.'" debe ser "'.$template->visibility.'"';
            }
        }
    }
    return($result);
}


function check_uml_methods($result, $className, $templateUmlMethodsList, $testUmlMethodsList){
    $prefix = 'En la clase "'.$className.'" ';
    $deltaCountMethods = sizeof($testUmlMethodsList) - sizeof($templateUmlMethodsList);
    if ($deltaCountMethods < 0){
        $result[] = "Faltan métodos UML. Cantidad faltante: ".abs($deltaCountMethods);
    }elseif ($deltaCountMethods > 0){
        $result[] = "Sobran métodos UML. Cantidad sobrante: ".abs($deltaCountMethods);
    }
    foreach($templateUmlMethodsList as $template){
        $found = array_filter($testUmlMethodsList, function($item) use ($template){
            if ( ! property_exists($item, "name")) return(False);
            return($item->name == $template->name);
        });
        if (sizeof($found) > 1){
            $result[] = $prefix.'existen métodos con el mismo nombre "'.$template->name.'"';
        }elseif (sizeof($found) <= 0){
            $result[] = $prefix.'no se encuentra el método "'.$template->name.'"';
        }else{
            if (reset($found)->type != $template->type){
                $result[] = $prefix.'el método "'.$template->name.'" debe ser tipo "'.$template->type.'"';
            }
            if (reset($found)->visibility != $template->visibility){
                $result[] = $prefix.'la visibilidad del método "'.$template->name.'" debe ser "'.$template->visibility.'"';
            }
            if (property_exists($template, "param1Name")){
                if (property_exists(reset($found), "param1Name")){
                    if (reset($found)->param1Name != $template->param1Name){
                        $result[] = $prefix.'el método "'.$template->name.'" debe tener un parámetro llamado "'.$template->param1Name.'"';
                    }
                }else{
                    $result[] = $prefix.'el método "'.$template->name.'" debe tener un parámetro llamado "'.$template->param1Name.'"';
                }
                if (property_exists($template, "param1Type")){
                    if (property_exists(reset($found), "param1Type")){
                        if (reset($found)->param1Type != $template->param1Type){
                            $result[] = $prefix.'el método "'.$template->name.'" debe tener un parámetro "'.$template->param1Name.'" con tipo de dato "'.$template->param1Type.'"';
                        }
                    }else{
                        $result[] = $prefix.'al método "'.$template->name.'" le falta el tipo de dato del parámetro';
                    }
                }
            }
        }
    }
    return($result);
}




function check_uml_classes($result, $templateUmlClassesList, $testUmlClassesList){
    $deltaCountClass = sizeof($testUmlClassesList) - sizeof($templateUmlClassesList);
    if ($deltaCountClass < 0){
        $result[] = "Faltan clases UML. Cantidad faltante: ".abs($deltaCountClass);
    }elseif ($deltaCountClass > 0){
        $result[] = "Sobran clases UML. Cantidad sobrante: ".abs($deltaCountClass);
    }
    foreach($templateUmlClassesList as $template){
        $found = array_filter($testUmlClassesList, function($item) use ($template){
            if (! property_exists($item, "name")) return(False);
            return($item->name == $template->name);
        });
        if (sizeof($found) > 1){
            $result[] = 'Existen clases con el mismo nombre "'.$template->name.'"';
        }elseif (sizeof($found) <= 0){
            $result[] = 'No se encuentra la clase "'.$template->name.'"';
        }else{
            if (property_exists(reset($found), "properties")){
                $result = check_uml_properties($result, reset($found)->name, $template->properties, reset($found)->properties);
            }else{
                $result[] = 'Faltan las propiedades de la clase "'.$template->name.'"';
            }
            if (property_exists(reset($found), "methods")){
                $result = check_uml_methods($result, reset($found)->name, $template->methods, reset($found)->methods);
            }else{
                $result[] = 'Faltan los métodos de la clase "'.$template->name.'"';
            }
        }
    }
    return($result);
}


function check_uml_links($result, $templateUmlLinksList, $testUmlLinksList, $templateUmlClassesList){
    $deltaCountLinks = sizeof($testUmlLinksList) - sizeof($templateUmlLinksList);
    if ($deltaCountLinks < 0){
        $result[] = "Faltan enlaces UML. Cantidad faltante: ".abs($deltaCountLinks);
    }elseif ($deltaCountLinks > 0){
        $result[] = "Sobran enlaces UML. Cantidad sobrante: ".abs($deltaCountLinks);
    }
    //Crear lista de las clases indexadas por su ID o modificar los enlaces poniendo los nombres de clase donde estan los id
    foreach($templateUmlLinksList as $template){
        if ((property_exists($template, "from")) &&
            (property_exists($template, "to")) &&
            (property_exists($template, "relationType"))){
            $found = array_filter($testUmlLinksList, function($item) use ($template){
                if ((! property_exists($item, "from")) ||
                    (! property_exists($item, "to")) ||
                    (! property_exists($item, "relationType"))) return(False);
                return(($item->from == $template->from) &&
                        ($item->to == $template->to) &&
                        ($item->relationType == $template->relationType));
            });
            if (sizeof($found) > 1){
                $result[] = 'Existen enlaces redundantes entre las clases "'.$templateUmlClassesList[-1-$template->from]->name.'" y "'.$templateUmlClassesList[-1-$template->to]->name.'"';
            }elseif (sizeof($found) <= 0){
                $from=$template->from;
                //$result[]=json_encode($template);
                $result[] = 'Falta un enlace entre las clases "'.$templateUmlClassesList[-1-$template->from]->name.'" y "'.$templateUmlClassesList[-1-$template->to]->name.'"';
            }else{
                if ($template->relationType != reset($found)->relationType){
                    $result[] = 'El enlace entre las clases "'.$templateUmlClassesList[-1-$template->from]->name.'" y "'.$templateUmlClassesList[-1-$template->to]->name.'" debe ser "'.$template->relationType.'"';
                }
            }
        }
    }
    return($result);
}

/**
 * Compara dos cadenas JSON que representan a graficos UML construidos mediante GoJS.
 * Los objetos son cadenas de texto con un JSON.
 * Uno de los objetos se toma como plantilla y el otro es el que se somete 
 * a prueba para ver si es similar a la plantilla. 
 * Lo que se hace es comparar la estructura lógica y se ignora la representación visual.
 * @param string $template Contiene el objeto JSON que representa al diagrama plantilla.
 * @param string $test Contiene el objeto JSON que se debe comparar con la plantilla.
 * @return array Devuelve un array vacio si no hay errores. De lo contrario, cada posición 
 * del array contien las cadenas que describen el error encontrado en el objeto $test.
 */
function check_uml_diagram($template, $test){
    //$template = json_decode($template); 
    //$test = json_decode($test);
    $result = [];
    try{
        if ((!isset($template)) || (!isset($test))){
            $result[] = "Uno de los diagramas a comparar no ha sido establecido.";
            return($result);
        }elseif ((!is_object($template)) || (!is_object($test))){ 
            $result[] = "Uno de los diagramas a comparar no es un objeto.";
            return($result);
        }elseif ((!property_exists($template, "nodeDataArray")) || (!property_exists($test, "nodeDataArray"))){
            $result[] = "El diagrama no tiene clases UML";
            return($result);
        }elseif ((!property_exists($template, "linkDataArray")) || (!property_exists($test, "linkDataArray"))){
            $result[] = "El diagrama no tiene enlaces UML";
            return($result);
        }
        $result = check_uml_classes($result, $template->nodeDataArray, $test->nodeDataArray);
        $aux=getNamesArray($template->nodeDataArray);
        $result = check_uml_links($result, $template->linkDataArray, $test->linkDataArray, $template->nodeDataArray);
    }
    catch(Exception $e){
        $result[] = "Ocurrio algún error de llave no existente o desconocido.";
    }
    return $aux;//$result;
}


?>


