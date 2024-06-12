<?php

namespace App\DataFixtures;
use App\Entity\NActivity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class NActivityFixtures extends Fixture
{



    public function load(ObjectManager $manager): void
    {
        $ac = new NActivity();
        $ac->setTitle("Ejercicio 1 :Estructura de Clase");
$ac->setDescription("Cree una clase Persona con atributo nombre y fecha de nacimiento(fechaNac) de la persona.
Defina una  operación que obtenga la edad actual(EdadActual()).Recuerde hacer una correcta definición de los tipos de datos
correspondientes por cada atributo.

");
$ac->setPlace("Docente ");
        $manager->persist($ac);

        $ad2 = new NActivity();
        $ad2->setTitle("Ejercicio 2: Estructura de Clase");
    $ad2->setDescription("Cree una clase Estudiante con atributo horario clase(horarioClase),nombre de escuela(nombreEsc),cantidaHorasEstudio y 
    defina operación cantidad de horas de estudio(cHorasEstudio()),devolverá cantidad de horas
   de estudio del estudiante en cuestión.Recuerde hacer una correcta definición de los tipos de datos correspondientes a 
   cada atributo.");
   $ad2->setPlace("Docente");
        $manager->persist($ad2);


        $ad3 = new NActivity();

    $ad3->setTitle("Ejercicio 3:Modelado UML");

    $ad3->setDescription("A partir de la plantilla de  diagrama UML con componentes edite el diagrama con la definición de clases de  las actividades anteriores
    teniendo en cuenta que un estudiante hereda de clase Persona sus atributos y métodos.
    ");
    $ad3->setPlace("Docente");

        $manager->persist($ad3);

        $ad4 = new NActivity();
    $ad4->setTitle("Ejercicio 4:Implementación ");

    $ad4->setDescription("En un Curso hay muchos estudiantes  que hicieron exámenes de la asignatura Programación 1,de cada estudiante se sabe su nombre,notaExamen  y dni.Elabore una función que obtenga el nombre del estudiante que obtuvo la mayor nota .
Use las siguientes variables auxiliares para poder darle solución a la problemática descrita:
(mayor,pos).


    ");
    $ad4->setPlace("Docente");

        $manager->persist($ad4);

        $manager->flush();
    }
}
