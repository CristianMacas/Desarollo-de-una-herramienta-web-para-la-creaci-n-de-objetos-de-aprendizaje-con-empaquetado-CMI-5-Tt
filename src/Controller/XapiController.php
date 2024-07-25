<?php

namespace App\Controller;

use App\Entity\NActivity;
use App\Entity\Statement;
use DOMDocument;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;
use App\Twig\CustomTwigExtension;

class XapiController extends AbstractController
{
    

    /**
     * @Route("/{id}", name="app_xapi_activity", methods={"GET"})
     */

    public function CrearXMLActivity(NActivity $nActivity): Response
    {
        /**
         * Nombre del Archivo Base
         */
        $idactivy = $nActivity->getId();

        /**
         * Script que genera el archivo XML
         */
        $doc = new DOMDocument('1.0', 'UTF-8');

        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        //create the main tags, without values
        $tincan = $doc->createElement('tincan');
        $activities = $doc->createElement('activities');

        // create some tags with values
        $activity = $doc->createElement('activity');
        $name = $doc->createElement('name');
        $description = $doc->createElement('description');
        $launch = $doc->createElement('launch');
        //$langstringd = $doc->createElement('lang');
        //$langstringl = $doc->createElement('lang');
        $url = $doc->createElement('url');

        //create the second tag book with different namespace
        $namespace = 'http://projecttincan.com/tincan.xsd';
        $namespaceid = 'http://id.tincanapi.com/activity/tincan-prototypes/' . $idactivy;
        $namespacetype = 'http://adlnet.gov/expapi/activities/course';
        $nameslang = 'en-US';
        $url = "index.html";

        //include the namespace prefix in the books tag
        $tincan->setAttribute('xmlns', $namespace);
        $activity->setAttribute('id', $namespaceid);
        $activity->setAttribute('type', $namespacetype);

        $description->setAttribute('lang', $nameslang);
        $launch->setAttribute('lang', $nameslang);

        //create the XML structure
        $tincan->appendChild($activities);
        $activities->appendChild($activity);
        $activity->appendChild($name);
        $activity->appendChild($description);
        $activity->appendChild($launch);

        $doc->appendChild($tincan);

        //create the node
        $textname = $doc->createTextNode($nActivity->getTitle());
        $textname = $name->appendChild($textname);
        $textdescription = $doc->createTextNode($nActivity->getDescription());
        $textdescription = $description->appendChild($textdescription);
        $textlaunch = $doc->createTextNode($url);
        $textlaunch = $launch->appendChild($textlaunch);

        $doc->save("tincan.xml");

        $server='localhost';
    	$database='xperienc_uml';
    	$user='xperienc';
    	$passw='Zb(nY;w1A62r1R';
    
    	$conn = mysqli_connect($server,$user,$passw,$database);
    	$conn->set_charset('utf8');
    	
    	$sql= "SELECT course.name, model_diagram_success.nactivity_id FROM course
	, model_diagram_success
WHERE course.id = model_diagram_success.course_id AND model_diagram_success.nactivity_id = '$idactivy'";
		$result=mysqli_query($conn,$sql);
		$count=mysqli_num_rows($result);
		$mostrar= '';
		if (mysqli_num_rows($result)>0)
		{
			$mostrar=mysqli_fetch_array($result)[0];
		}
		
        /**
         * Nombre del Archivo Base
         */
        
         
        $idactivy = $nActivity->getId();

        /**
         * Script que genera el html
         */
        $html = $this->createHtml($nActivity->getTitle(), $nActivity->getDescription());
        $fp = fopen('index.html', 'w') or die("error creando fichero!");
        fwrite($fp, $html);
        fclose($fp);

        /**
         * Script que selecciona multiples archivos
         */

        $fileName = $mostrar.'.'.$nActivity->getTitle() . '.zip';
        $files = array('tincan.xml', 'index.html');

        /**
         * Script que crear el archivo ZIP
         */
        $result = $this->createZipArchive($files, $fileName);

        header("Content-Disposition: attachment; filename=\"" . $fileName . "");
        header("Content-Length: " . filesize($fileName));
        readfile($fileName);

        return $this->render('n_activity/show.html.twig', [
            'n_activity' => $nActivity,
        ]);
    }

    /**
     * Metodo que crea el archivo ZIP
     * @param array $files
     * @param string $destinattion
     * @param boolean $overwrite
     */
    public function createZipArchive($files = array(), $destination = '', $overwrite = false)
    {
        if (file_exists($destination) && !$overwrite) {
            return false;
        }
        $validFiles = array();
        if (is_array($files)) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $validFiles[] = $file;
                }
            }
        }
        if (count($validFiles)) {
            $zip = new ZipArchive();
            if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) == true) {
                foreach ($validFiles as $file) {
                    $zip->addFile($file, $file);
                }
                $zip->close();
                return file_exists($destination);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Metodo que genera el html
     *
     * @param string $titlehtml
     * @param string $descripcionhtml
     * @return string $content
     *
     * 
     */

    public function createHtml($titlehtml, $descripcionhtml)
    {

        $content = '<!DOCTYPE html>
      <html lang="en">
      <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Desarrollo de una aplicación web para la creación de objetos de aprendizaje con empaquetado CMI-5.</title>
      </head>
      <body>
          <div>
              <table style="width: 100%; text-align:center;">
                  <tr>
                  <td style="border-width: 1px;border: solid; background-color: silver;" width="100%" colspan="3"><b>CURSO</b></td>
                  </tr>
                  <tr>
                      <td style="border-width: 1px;border: solid;" width="33%">ACTVIDAD</td>
                  </tr>
                  <tr>
                      <td style="border-width: 1px;border: solid;" width="33%">' . $titlehtml . '</td>
                  </tr>
                  <tr>
                      <td style="border-width: 1px;border: solid;" width="33%">' . $descripcionhtml . '</td>
                  </tr>
              </table>
          </div>
      </body>
      </html>';

        return $content;
    }

/**
 * Metodo para Dashboard xApi
 *@author
 *
 */

    /**
     * @Route("xapi/dashboard", name="app_xapi_dashboard")
     */
    public function DashBoardXapi(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $actividades = [];
        $verbos=[];

        $statements = $em->getRepository('App:Statement')->findAll();

          
        /** @var Statement $st */
        foreach ($statements as $key => $st) {

      
            $actividades[] =$st->getObject()['definition'];

            $verbos[]=$st->getVerb()['display'];
    


         
        }
        return $this->render('xapi/index.html.twig', [
            'statements' => $statements,
            'actividades'=>$actividades,
            'verbos'=>$verbos,
      


        ]);
    }

/**
 * Tabla de declaraciones xApi
 *
 *
 */

    /**
     * @Route("xapi/tablestatements", name="app_xapi_tablestatements")
     */
    public function tableStatementsXapi(): Response
    {

        $em = $this->getDoctrine()->getManager();

        $statements = $em->getRepository('App:Statement')->FindAllStatement();
        $actividades = [];
        $verbos=[];

       
  
        /** @var Statement $st */
        /*foreach ($statements as $key => $st) {

      
            $actividades[] =$st['object']['definition'];

            $verbos[]=$st['verb']['display'];
    


         
        }*/

     
      
        return $this->render('xapi/declaraciones.html.twig', [
            'statements' => $statements,
        ]);
    }
    /**
     * @Route("xapi/statement/{id}", name="app_xapi_statement_pdf")
     */
    public function PdfActionStatement(Pdf $pdf, Statement $st)
    {
        $em = $this->getDoctrine()->getManager();
        $html = $this->renderView('pdf/show_statement.html.twig', array('st' => $st));
        $pdf->setTemporaryFolder('c:\xampp\htdocs\v2cristian\temp');
        return new PdfResponse(
            $pdf->getOutputFromHtml($html,
                array('lowquality' => false,
                    'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'A4',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Estadísticas xApi',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="xapi.pdf"',
            )
        );
    }

/**
 * Método para declaración en formato json
 *
 *
 */
    /**
     * @Route("xapi/json/{id}", name="app_xapi_statement_json")
     */
    public function JsonActionStatement(Statement $st)
    {

        $response = new Response();
        $response->setContent(
            json_encode(
                [
                    'data' => array(
                        "actor" => $st->getActor(),
                        "verb" => $st->getVerb(),
                        "object" => $st->getObject(),
                        "result" => $st->getResult(),
                        "context" => $st->getContext()
                    )
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ) 
        );
        $response->headers->set('Content-Type', 'application/json',
            'Content-Disposition', 'attachment;charset=UTF-8;filename="statement.json"'
        );

        return $response;
    }

}
