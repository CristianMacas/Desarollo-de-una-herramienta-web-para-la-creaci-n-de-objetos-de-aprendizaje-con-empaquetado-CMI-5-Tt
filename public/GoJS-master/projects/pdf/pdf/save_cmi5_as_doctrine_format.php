<?php

/**
 * Para guardar los datos en la base de datos como si fuese realizado por Doctrine.
 * Esto es para serializar los datos y que sean compatibles con el modo de almacenamiento
 * de Doctrine de Sympony en PHP. Esto es para los datos de una tabla especifica.
 * Los datos corresponden al estandar cmi5.
 */

function createActor($name, $mbox){
    $actor = array(
        'name' => (string)$name,
        'mbox' => (string)$mbox
    );
    return(serialize($actor));    
}


function createVerb($verbType){
    $verb = null;
    switch ($verbType) {
        case 'launched':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/lanzado',
                'display' => array('en-US' => 'launched', 'es' => 'lanzado')
            );
            break;
        case 'initialized':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/inicializado',
                'display' => array('en-US' => 'initialized', 'es' => 'inicializado')
            );
            break;
        case 'terminated':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/terminado',
                'display' => array('en-US' => 'terminated', 'es' => 'terminado')
            );
            break;
        case 'abandoned':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/abandonado',
                'display' => array('en-US' => 'abandoned', 'es' => 'abandonado')               
            );
            break;
        case 'waived':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/renunciado',
                'display' => array('en-US' => 'waived', 'es' => 'renunciado')
            );
            break;
        case 'failed':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/fallado',
                'display' => array('en-US' => 'failed', 'es' => 'fallado')
            );
            break;
        case 'passed':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/pasado',
                'display' => array('en-US' => 'passed', 'es' => 'pasado')
            );
            break;
        case 'completed':
            $verb = array(
                'id' => 'http://adlnet.gov/expapi/verbs/completado',
                'display' => array('en-US' => 'completed', 'es' => 'completado')
            );
            break;
    }
    return(serialize($verb));    
}


function createObject($objectType, $id, $definition){
    $object = array(
        'objectType' => (string)$objectType,
        'id' => (string)$id,
        'definition' => (string)$definition
    );
    return(serialize($object));    
}


function createResult($completion, $success){
    $result = array(
        'completion' => (bool)$completion,
        'success' => (bool)$success
    );
    return(serialize($result));    
}


function createContext($instructor, $team, $contextActivities, $platform, $extensions){
    $context = array(
        'instructor' => (string)$instructor,
        'team' => (string)$team,
        'contextActivities' => (string)$contextActivities,
        'platform' => (string)$platform,
        'extensions' => (string)$extensions
    );
    return(serialize($context));    
}


function createAuthority(){
    return(serialize(null));    
}


function createAttachments(){
    return(serialize(null));    
}


function generate_custom_uuid_v4() {
    // Generate 16 bytes (128 bits) of random data.
    $data = random_bytes(16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}



/**
 * Guardar los datos en la base de datos como si fuese realizado por Doctrine.
 * Esto es para serializar los datos y que sean compatibles con el modo de almacenamiento
 * de Doctrine de Sympony en PHP. Esto es para los datos de la tabla statement.
 * Los datos corresponden a los del estandar cmi5.
 * 
 * @param string $name Nombre del alumno que realiza el ejercicio.
 * @param string $mbox Email del alumno que realiza el ejercicio.
 * @param string $verb Verbo que representa el estado del usuario en la actividad.
 * @param string $objectType Esto se pasa cadena vacia.
 * @param string $id Esto se pasa cadena vacia.
 * @param string $definition Esto se pasa cadena vacia.
 * @param bool $completion True si la tarea está completada.
 * @param bool $success True si el resultado de la tarea ha sido exitoso.
 * @param string $instructor Nombre del profesor que creó el ejercicio.
 * @param string $team Grupo al que pertenece.
 * @param string $contextActivities Nombre del curso al que pertenece el ejercicio.
 * @param string $platform Nombre del programa donde se crea y ejecuta la tarea.
 * @param string $extensions
 */
function saveStatement(
    $name, $mbox, 
    $verb, 
    $objectType, $id, $definition, 
    $completion, $success, 
    $instructor, $team, $contextActivities, $platform, $extensions
){
    //$actor = createActor();
    $id = generate_custom_uuid_v4(); 
    $actor = createActor($name, $mbox);
    $verb = createVerb($verb);
    $object = createObject($objectType, $id, $definition);
    $result = createResult($completion, $success);
    $context = createContext($instructor, $team, $contextActivities, $platform, $extensions);

    // Zonas horarias en https://www.php.net/manual/es/timezones.america.php
    $dt = new DateTime("now", new DateTimeZone('America/Guayaquil'));
    $timestamp = $dt->format("Y-m-d H:i:s");  

    $authority = createAuthority();
    $attachments = createAttachments();

    $conection = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
    
    
    $result = mysqli_query(
        $conection, 
        "insert into statement values ('".$id."', '".$actor."', '".$verb."', '".$object."', '".$result."', '".$context."', '".$timestamp."', '".$authority."', '".$attachments."')"
    );
    $conection->close();
}


?>