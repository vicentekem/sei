<?php

require_once "../config/Conexion.php";
require_once "../models/repositories/PostulanteRepository.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Instantiation and passing `true` enables exceptions

class PostulanteValidator
{

    private $postulanteRepository;

    public function __construct()
    {
        $this->postulanteRepository = new PostulanteRepository();
    }

    function sendEmail($data){

        // Load Composer's autoloader
        require '../vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->CharSet = "UTF-8";
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'joel.vicente.quispe@gmail.com';                     // SMTP username
            $mail->Password   = 'joel123.';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('joel.vicente.quispe@gmail.com', 'SISTEMA DE POSTULACIÓN CAS - DIRIS LIMA CENTRO');
            $mail->addAddress($data["correo"]);
            $mail->addReplyTo('joel.vicente.quispe@gmail.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Postulacion CAS - confirmación de correo';

            $template = "../views/credenciales.php";

            $message = file_get_contents($template);
            $message = str_replace('{{usuario}}', $data["nro_doc"], $message);
            $message = str_replace('{{clave}}', $data["clave"], $message);
            $message = str_replace('{{dni}}', $data["nro_doc"], $message);
            $message = str_replace('{{nombres}}', $data["nombres"], $message);
            $message = str_replace('{{url_base}}', "http://10.0.1.94/convocatorias",$message);

            $mail->msgHTML( $message );

            $resul = $mail->send();

        } catch (Exception $e) {

        }
    }

    function getDatosSustentatorios()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else {
            $array_result = $this->postulanteRepository->getDatosSustentatorios($id_postulante);
        }

        return $array_result;
    }

    function getDatosPersonales()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else {
            $array_result = $this->postulanteRepository->getDatosPersonales($id_postulante);
        }

        return $array_result;
    }

    function getFormacionAcademica()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else {
            $array_result = $this->postulanteRepository->getFormacionAcademica($id_postulante);
        }

        return $array_result;
    }

    function getExperienciaLaboral()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;
        $id_convocatoria = isset($_GET["id_convocatoria"]) ? $_GET["id_convocatoria"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else if ($id_convocatoria == 0) {
            $array_result["error"] = "El id de la convocatoria es requerido";
        } else {
            $array_result = $this->postulanteRepository->getExperienciaLaboral($id_postulante, $id_convocatoria);
        }

        return $array_result;
    }

    function getReferencias()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;
        $id_convocatoria = isset($_GET["id_convocatoria"]) ? $_GET["id_convocatoria"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else if ($id_convocatoria == 0) {
            $array_result["error"] = "El id de la convocatoria es requerido";
        } else {
            $array_result = $this->postulanteRepository->getReferencias($id_postulante, $id_convocatoria);
        }

        return $array_result;
    }

    function getConocimientos()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;
        $id_convocatoria = isset($_GET["id_convocatoria"]) ? $_GET["id_convocatoria"] : 0;
        $tipo_conocimiento = isset($_GET["tipo_conocimiento"]) ? $_GET["tipo_conocimiento"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else if ($id_convocatoria == 0) {
            $array_result["error"] = "El id de la convocatoria es requerido";
        } else if ($tipo_conocimiento == 0) {
            $array_result["error"] = "El tipo de conocimiento es requerido";
        } else {
            $array_result = $this->postulanteRepository->getConocimientos($id_postulante, $id_convocatoria, $tipo_conocimiento);
        }

        return $array_result;
    }

    function getPublicaciones()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;
        $id_convocatoria = isset($_GET["id_convocatoria"]) ? $_GET["id_convocatoria"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else if ($id_convocatoria == 0) {
            $array_result["error"] = "El id de la convocatoria es requerido";
        } else {
            $array_result = $this->postulanteRepository->getPublicaciones($id_postulante, $id_convocatoria);
        }

        return $array_result;
    }

    function getExposiciones()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;
        $id_convocatoria = isset($_GET["id_convocatoria"]) ? $_GET["id_convocatoria"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else if ($id_convocatoria == 0) {
            $array_result["error"] = "El id de la convocatoria es requerido";
        } else {
            $array_result = $this->postulanteRepository->getExposiciones($id_postulante, $id_convocatoria);
        }

        return $array_result;
    }

    function getAnexos()
    {
        $id_postulante = isset($_GET["id_postulante"]) ? $_GET["id_postulante"] : 0;
        $id_convocatoria = isset($_GET["id_convocatoria"]) ? $_GET["id_convocatoria"] : 0;

        if ($id_postulante == 0) {
            $array_result["error"] = "El id del postulante es requerido";
        } else if ($id_convocatoria == 0) {
            $array_result["error"] = "El id de la convocatoria es requerido";
        } else {
            $array_result = $this->postulanteRepository->getAnexos($id_postulante, $id_convocatoria);
        }

        return $array_result;
    }

    public function register()
    {
        $result = ["error" => ""];

        $data["nacionalidad"] = isset($_POST['nacionalidad']) ? $_POST['nacionalidad'] : 0;
        $data["tipo_doc"] = isset($_POST['tipo_doc']) ? $_POST['tipo_doc'] : 0;
        $data["nro_doc"] = isset($_POST['nro_doc']) ? $_POST['nro_doc'] : "";
        $data["nombres"] = isset($_POST['nombres']) ? $_POST['nombres'] : "";
        $data["apellido_pat"] = isset($_POST['apellido_pat']) ? $_POST['apellido_pat'] : "";
        $data["apellido_mat"] = isset($_POST['apellido_mat']) ? $_POST['apellido_mat'] : "";
        $data["correo"] = isset($_POST['correo']) ? $_POST['correo'] : "";
        $data["clave"] = isset($_POST['clave']) ? $_POST['clave'] : "";
        $data["clave_conf"] = isset($_POST['clave_conf']) ? $_POST['clave_conf'] : "";

        if($data["nacionalidad"] === 0) $result["error"] = "Seleccione nacionalidad";
        else if($data["tipo_doc"] === 0) $result["error"] = "Seleccione tipo de documento";
        else if($data["nro_doc"] ==="") $result["error"] = "Ingrese su número de documento";
        else if($data["nombres"] ==="" ) $result["error"] = "Ingrese su nombre";
        else if($data["apellido_pat"] === "") $result["error"] = "Ingrese su apellido paterno";
        else if($data["apellido_mat"] === "") $result["error"] = "Ingrese su apellido materno";
        else if($data["correo"] === "") $result["error"] = "Ingrese su correo electrónico";
        else if($data["clave"] === "") $result["error"] = "Ingrese contraseña";
        else if($data["clave_conf"] === "") $result["error"] = "Ingrese confirmación de la contraseña";
        else if($data["clave"] !== $data["clave_conf"]) $result["error"] = "Contraseñas no coinsiden";

        if ($result["error"] === "") {

            $result = $this->postulanteRepository->register($data);

            if($result["error"] === ""){
                $this->sendEmail($data);
            }

        }

        return $result;
    }

    public function setDatosSustentatorios()
    {

        $result = ["error" => ""];

        $sustento_doc = isset($_FILES['sustento_doc']) ? $_FILES['sustento_doc'] : "";
        $tipo_doc = isset($_POST['tipo_doc']) ? $_POST['tipo_doc'] : "";
        $id_postulante = isset($_POST['id_postulante']) ? $_POST['id_postulante'] : 0;

        if ($sustento_doc === "") {
            $result["error"] = "Seleccione un archivo";
        }

        if ($tipo_doc === "") {
            $result["error"] = "Tipo de documento es requerido";
        }

        if ($id_postulante === 0) {
            $result["error"] = "El id del postulante es requerido";
        }

        if ($result["error"] === "") {

            $datos_sus_act = $this->postulanteRepository->getDatosSustentatorios($id_postulante);

            $url_actual = isset($datos_sus_act["row"]) ? "../" . $datos_sus_act["row"]["sustento_doc"] : "";

            //$name = $sustento_doc["name"];
            $temp = $sustento_doc["tmp_name"];

            //$name = basename($name,".pdf");

            $url = "../public/documents/" . "doc_" . str_pad($id_postulante , 5, "0", STR_PAD_LEFT) . ".pdf";
            $url_data_base = "public/documents/" . "doc_" . str_pad($id_postulante , 5, "0", STR_PAD_LEFT) . ".pdf";

            $ok = move_uploaded_file($temp, $url);

            if ($ok) {

                $datos_sustentatorios["tipo_doc"] = $tipo_doc;
                $datos_sustentatorios["sustento_doc_url"] = $url_data_base;
                $datos_sustentatorios["id_postulante"] = $id_postulante;

                $result = $this->postulanteRepository->setDatosSustentatorios($datos_sustentatorios);

                if ($result["error"] === "" && ($url_actual !== $url) && $url_actual != "" ) {
                    unlink($url_actual);
                }

            } else {
                $result["error"] = "No se pudo subir el archivo seleccionado";
            }

        }

        return $result;
    }

    public function setDatosPersonales()
    {

        $result = ["error" => ""];

        $datos_personales['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $datos_personales['nacionalidad'] = isset($_POST['nacionalidad']) ? $_POST['nacionalidad'] : "";
        $datos_personales['tipo_doc'] = isset($_POST['tipo_doc']) ? $_POST['tipo_doc'] : "";
        $datos_personales['estado_civil'] = isset($_POST['estado_civil']) ? $_POST['estado_civil'] : "";
        $datos_personales['departamento'] = isset($_POST['departamento']) ? $_POST['departamento'] : "";
        $datos_personales['provincia'] = isset($_POST['provincia']) ? $_POST['provincia'] : "";
        $datos_personales['distrito'] = isset($_POST['distrito']) ? $_POST['distrito'] : "";
        $datos_personales['tipo_zona'] = isset($_POST['tipo_zona']) ? $_POST['tipo_zona'] : "";
        $datos_personales['tipo_via'] = isset($_POST['tipo_via']) ? $_POST['tipo_via'] : "";
        $datos_personales['nro_doc'] = isset($_POST['nro_doc']) ? $_POST['nro_doc'] : "";
        $datos_personales['apellido_pat'] = isset($_POST['apellido_pat']) ? $_POST['apellido_pat'] : "";
        $datos_personales['apellido_mat'] = isset($_POST['apellido_mat']) ? $_POST['apellido_mat'] : "";
        $datos_personales['nombres'] = isset($_POST['nombres']) ? $_POST['nombres'] : "";
        $datos_personales['nombre_zona'] = isset($_POST['nombre_zona']) ? $_POST['nombre_zona'] : "";
        $datos_personales['direccion'] = isset($_POST['direccion']) ? $_POST['direccion'] : "";
        $datos_personales['telefono'] = isset($_POST['telefono']) ? $_POST['telefono'] : "";
        $datos_personales['celular'] = isset($_POST['celular']) ? $_POST['celular'] : "";
        $datos_personales['correo'] = isset($_POST['correo']) ? $_POST['correo'] : "";
        $datos_personales['discapacidad'] = isset($_POST['discapacidad']) ? $_POST['discapacidad'] : -1;
        $datos_personales['licenciado_ffaa'] = isset($_POST['licenciado_ffaa']) ? $_POST['licenciado_ffaa'] : -1;

        //validacion de datos en el backend
        if ($datos_personales['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if (trim($datos_personales['nacionalidad']) === "") $result["error"] = "Seleccione Nacionalidad";
        else if (trim($datos_personales['tipo_doc']) === "") $result["error"] = "Selecciones Tipo de documento";
        else if (trim($datos_personales['estado_civil']) === "") $result["error"] = "Seleccione estado civil";
        else if (trim($datos_personales['departamento']) === "") $result["error"] = "Seleccione departamento";
        else if (trim($datos_personales['provincia']) === "") $result["error"] = "Seleccione provincia";
        else if (trim($datos_personales['distrito']) === "") $result["error"] = "Seleccione distrito";
        else if (trim($datos_personales['tipo_zona']) === "") $result["error"] = "Seleccione tipo de zona";
        else if (trim($datos_personales['tipo_via']) === "") $result["error"] = "Seleccione tipo de vía";
        else if (trim($datos_personales['nro_doc']) === "") $result["error"] = "Ingrese su número de documento";
        else if (trim($datos_personales['apellido_pat']) === "") $result["error"] = "Ingrese su apellido paterno";
        else if (trim($datos_personales['apellido_mat']) === "") $result["error"] = "Ingrese su apellido materno";
        else if (trim($datos_personales['nombres']) === "") $result["error"] = "Ingrese su nombre completo";
        else if (trim($datos_personales['nombre_zona']) === "") $result["error"] = "Ingrese nombre de zona";
        else if (trim($datos_personales['direccion']) === "") $result["error"] = "Ingrese dirección";
        else if (trim($datos_personales['celular']) === "") $result["error"] = "Ingrese su número celular";
        else if (trim($datos_personales['correo']) === "") $result["error"] = "Ingrese su correo";
        else if (trim($datos_personales['discapacidad']) === "") $result["error"] = "Debes consignar si cuentas con alguna discapacidad";
        else if (trim($datos_personales['licenciado_ffaa']) === "") $result["error"] = "Debes consignar si perteneces a las FF.AA ";

        if ($result["error"] === "") {

            $result = $this->postulanteRepository->setDatosPersonales($datos_personales);

        }

        return $result;

    }

    public function setFormacionAcademica()
    {
        $result = ["error" => ""];

        $sustento_doc = isset($_FILES['file_sustento']) ? $_FILES['file_sustento'] : "";

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['nivel'] = isset($_POST['nivel']) ? $_POST['nivel'] : "";
        $data['grado'] = isset($_POST['grado']) ? $_POST['grado'] : "";
        $data['especialidad'] = isset($_POST['especialidad']) ? $_POST['especialidad'] : "";
        $data['centro_estudios'] = isset($_POST['centro_estudios']) ? $_POST['centro_estudios'] : "";
        $data['inicio_year'] = isset($_POST['inicio_year']) ? $_POST['inicio_year'] : "";
        $data['fin_year'] = isset($_POST['fin_year']) ? $_POST['fin_year'] : "";
        $data['fecha_extension_grado'] = isset($_POST['fecha_extension_grado']) ? $_POST['fecha_extension_grado'] : "";

        $data['fecha_extension_grado'] = $data['fecha_extension_grado'] == "" ? null : $data['fecha_extension_grado'] ;

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if (trim($data['nivel']) === "") $result["error"] = "Seleccione nivel";
        else if (trim($data['grado']) === "") $result["error"] = "Selecciones grado";
        else if (trim($data['especialidad']) === "") $result["error"] = "Ingrese Especialidad ";
        else if (trim($data['centro_estudios']) === "") $result["error"] = "Ingrese centro de estudios";
        else if (trim($data['inicio_year']) === "") $result["error"] = "Ingrese año de inico";
        else if (trim($data['fin_year']) === "") $result["error"] = "Ingrese año de finalización";
        //else if (trim($data['fecha_extension_grado']) === "") $result["error"] = "Ingrese fecha de extensión de grado";
        else if ($sustento_doc === "") $result["error"] = "Seleccione un archivo";

        if ($result["error"] === "") {

            $name = $sustento_doc["name"];
            $temp = $sustento_doc["tmp_name"];

            $url = "../public/documents/" . $data["id"] . $data["nivel"] . $data["grado"] . "-formacion" . $name;
            $url_data_base = "public/documents/" . $data["id"] . $data["nivel"] . $data["grado"] . "-formacion" . $name;

            $ok = move_uploaded_file($temp, $url);

            if ($ok) {

                $data["sustento"] = $url_data_base;

                $result = $this->postulanteRepository->setFormacionAcademica($data);

            } else {
                $result["error"] = "No se pudo subir el archivo seleccionado";
            }

        }

        return $result;
    }

    public function setExperienciaLaboral()
    {
        $result = ["error" => ""];

        $sustento_doc = isset($_FILES['file_sustento']) ? $_FILES['file_sustento'] : "";

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;

        $data['tipo_experiencia'] = isset($_POST['tipo_experiencia']) ? $_POST['tipo_experiencia'] : "";
        $data['cargo'] = isset($_POST['cargo']) ? $_POST['cargo'] : "";
        $data['tipo_entidad'] = isset($_POST['tipo_entidad']) ? $_POST['tipo_entidad'] : "";
        $data['nombre_entidad'] = isset($_POST['nombre_entidad']) ? $_POST['nombre_entidad'] : "";
        $data['fecha_inicio'] = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : "";
        $data['fecha_fin'] = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : "";

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        else if (trim($data['tipo_experiencia']) === "") $result["error"] = "Selecciones el tipo de experiencia";
        else if (trim($data['cargo']) === "") $result["error"] = "Ingres el cargo";
        else if (trim($data['tipo_entidad']) === "") $result["error"] = "seleccione el tipo de entidad";
        else if (trim($data['nombre_entidad']) === "") $result["error"] = "Ingrese el nombre de la entidad";
        else if (trim($data['fecha_inicio']) === "") $result["error"] = "Ingrese año de inico";
        else if (trim($data['fecha_fin']) === "") $result["error"] = "Ingrese año de finalización";
        else if ($sustento_doc === "") $result["error"] = "Seleccione un archivo";

        if ($result["error"] === "") {

            $name = $sustento_doc["name"];
            $temp = $sustento_doc["tmp_name"];

            $url = "../public/documents/" . $data["id"] . "-exp" . $name;
            $url_data_base = "public/documents/" . $data["id"] . "-exp" . $name;

            $ok = move_uploaded_file($temp, $url);

            if ($ok) {

                $data["sustento"] = $url_data_base;

                $result = $this->postulanteRepository->setExperienciaLaboral($data);

            } else {
                $result["error"] = "No se pudo subir el archivo seleccionado";
            }

        }

        return $result;
    }

    public function setReferencias()
    {

        $result = ["error" => ""];

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;

        $data['nombre_referencia'] = isset($_POST['nombre_referencia']) ? $_POST['nombre_referencia'] : "";
        $data['cargo'] = isset($_POST['cargo']) ? $_POST['cargo'] : "";
        $data['nombre_entidad'] = isset($_POST['nombre_entidad']) ? $_POST['nombre_entidad'] : "";
        $data['tel_entidad'] = isset($_POST['tel_entidad']) ? $_POST['tel_entidad'] : "";

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        else if (trim($data['nombre_referencia']) === "") $result["error"] = "Ingrese nombre de referencia";
        else if (trim($data['cargo']) === "") $result["error"] = "Ingres el cargo";
        else if (trim($data['nombre_entidad']) === "") $result["error"] = "Ingrese el nombre de la entidad";
        else if (trim($data['tel_entidad']) === "") $result["error"] = "Ingrese el telefono de la entidad";


        if ($result["error"] === "") {

            $result = $this->postulanteRepository->setReferencias($data);
        }

        return $result;
    }

    public function setConocimientos()
    {

        $result = ["error" => ""];

        $sustento_doc = isset($_FILES['file_sustento']) ? $_FILES['file_sustento'] : "";

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;
        $data['tipo_conocimiento'] = isset($_POST["tipo_conocimiento"]) ? $_POST["tipo_conocimiento"] : 0;

        $data['tema'] = isset($_POST['tema']) ? $_POST['tema'] : null;
        $data['idioma'] = isset($_POST['idioma']) ? $_POST['idioma'] : null;
        $data['nivel'] = isset($_POST['nivel']) ? $_POST['nivel'] : null;
        $data['centro_estudios'] = isset($_POST['centro_estudios']) ? $_POST['centro_estudios'] : null;
        $data['inicio'] = isset($_POST['inicio']) ? $_POST['inicio'] : null;
        $data['fin'] = isset($_POST['fin']) ? $_POST['fin'] : null;
        $data['duracion'] = isset($_POST['duracion']) ? $_POST['duracion'] : null;
        $data['tipo_sustento'] = isset($_POST['tipo_sustento']) ? $_POST['tipo_sustento'] : null;

        //validacion de datos en el backend
        if ($data['id'] == 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";
        else if ($data['tipo_conocimiento'] === 0) $result["error"] = "El tipo de conocimiento es requerido";

        else if (trim($data['tema']) === "" && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese el tema";
        else if (trim($data['tema']) === null && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese el tema";
        else if (trim($data['centro_estudios']) === "" && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese el centro de estudios";
        else if (trim($data['centro_estudios']) === null && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese el centro de estudios";
        else if (trim($data['inicio']) === "" && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese la fecha de inicio";
        else if (trim($data['inicio']) === null && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese la fecha de inicio";
        else if (trim($data['fin']) === "" && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese la fecha finalización";
        else if (trim($data['fin']) === null && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese la fecha finalización";
        else if (trim($data['duracion']) === "" && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese la duración";
        else if (trim($data['duracion']) === null && $data["tipo_conocimiento"] != 3) $result["error"] = "Ingrese la duración";
        else if (trim($data['tipo_sustento']) === "" && $data["tipo_conocimiento"] != 3) $result["error"] = "Seleccione el tipo de sustento";
        else if (trim($data['tipo_sustento']) === null && $data["tipo_conocimiento"] != 3) $result["error"] = "Seleccione el tipo de sustento";

        else if (trim($data['idioma']) === "" && $data["tipo_conocimiento"] == 3) $result["error"] = "Seleccione el idioma";
        else if (trim($data['idioma']) === null && $data["tipo_conocimiento"] == 3) $result["error"] = "Seleccione el idioma";
        else if (trim($data['nivel']) === "" && $data["tipo_conocimiento"] == 3) $result["error"] = "seleccione el nivel";
        else if (trim($data['nivel']) === null && $data["tipo_conocimiento"] == 3) $result["error"] = "seleccione el nivel";

        else if ($sustento_doc === "") $result["error"] = "Seleccione un archivo";

        if ($result["error"] === "") {

            $name = $sustento_doc["name"];
            $temp = $sustento_doc["tmp_name"];

            $url = "../public/documents/" . $data["id"] . $data['tipo_conocimiento'] . "-con" . $name;
            $url_data_base = "public/documents/" . $data["id"] . $data['tipo_conocimiento'] . "-con" . $name;

            $ok = move_uploaded_file($temp, $url);

            if ($ok) {

                $data["sustento"] = $url_data_base;

                $result = $this->postulanteRepository->setConocimientos($data);

            } else {
                $result["error"] = "No se pudo subir el archivo seleccionado";
            }

        }

        return $result;
    }

    public function setPublicaciones()
    {

        $result = ["error" => ""];

        $sustento_doc = isset($_FILES['file_sustento']) ? $_FILES['file_sustento'] : "";

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;

        $data['editorial'] = isset($_POST['editorial']) ? $_POST['editorial'] : "";
        $data['titulo'] = isset($_POST['titulo']) ? $_POST['titulo'] : "";
        $data['grado_participacion'] = isset($_POST['grado_participacion']) ? $_POST['grado_participacion'] : "";
        $data['fecha_publicacion'] = isset($_POST['fecha_publicacion']) ? $_POST['fecha_publicacion'] : "";

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        else if (trim($data['editorial']) === "") $result["error"] = "Ingrese el editorial";
        else if (trim($data['titulo']) === "") $result["error"] = "Ingrese e título de la publicacion";
        else if (trim($data['grado_participacion']) === "") $result["error"] = "Ingrese el grado de participación";
        else if (trim($data['fecha_publicacion']) === "") $result["error"] = "Ingrese la fecha de publicación";

        else if ($sustento_doc === "") $result["error"] = "Seleccione un archivo";

        if ($result["error"] === "") {

            $name = $sustento_doc["name"];
            $temp = $sustento_doc["tmp_name"];

            $url = "../public/documents/" . $data["id"] . "-pub" . $name;
            $url_data_base = "public/documents/" . $data["id"] . "-pub" . $name;

            $ok = move_uploaded_file($temp, $url);

            if ($ok) {

                $data["sustento"] = $url_data_base;

                $result = $this->postulanteRepository->setPublicaciones($data);

            } else {
                $result["error"] = "No se pudo subir el archivo seleccionado";
            }

        }

        return $result;
    }

    public function setExposiciones()
    {
        $result = ["error" => ""];

        $sustento_doc = isset($_FILES['file_sustento']) ? $_FILES['file_sustento'] : "";

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;

        $data['institucion'] = isset($_POST['institucion']) ? $_POST['institucion'] : "";
        $data['tema'] = isset($_POST['tema']) ? $_POST['tema'] : "";
        $data['ciudad'] = isset($_POST['ciudad']) ? $_POST['ciudad'] : "";
        $data['fecha_evento'] = isset($_POST['fecha_evento']) ? $_POST['fecha_evento'] : "";

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        else if (trim($data['institucion']) === "") $result["error"] = "Ingrese la institución";
        else if (trim($data['tema']) === "") $result["error"] = "Ingrese el tema";
        else if (trim($data['ciudad']) === "") $result["error"] = "Ingrese la ciudad";
        else if (trim($data['fecha_evento']) === "") $result["error"] = "Ingrese la fecha del evento";

        else if ($sustento_doc === "") $result["error"] = "Seleccione un archivo";

        if ($result["error"] === "") {

            $name = $sustento_doc["name"];
            $temp = $sustento_doc["tmp_name"];

            $url = "../public/documents/" . $data["id"] . "-expo" . $name;
            $url_data_base = "public/documents/" . $data["id"] . "-expo" . $name;

            $ok = move_uploaded_file($temp, $url);

            if ($ok) {

                $data["sustento"] = $url_data_base;

                $result = $this->postulanteRepository->setExposiciones($data);

            } else {
                $result["error"] = "No se pudo subir el archivo seleccionado";
            }

        }

        return $result;
    }

    public function setAnexos()
    {
        $result = ["error" => ""];

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;

        $data['anexos'] = isset($_POST['anexos']) ? $_POST['anexos'] : "";

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        else if ($data['anexos'] === "") $result["error"] = "Responda las preguntas";
        else if (count($data['anexos']) === 0) $result["error"] = "Responda las preguntas";

        if ($result["error"] === "") {

            $result = $this->postulanteRepository->setAnexos($data);

        }
        return $result;
    }

    public function completePos(){

        $result = ["error" => ""];

        $data['id'] = isset($_POST['id']) ? $_POST['id'] : 0;
        $data['id_convocatoria'] = isset($_POST['id_convocatoria']) ? $_POST['id_convocatoria'] : 0;

        //validacion de datos en el backend
        if ($data['id'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['id_convocatoria'] === 0) $result["error"] = "El id de la convocatoria es requerido";


        if ($result["error"] === "") {

            $result = $this->postulanteRepository->completePos($data);

        }

        return $result;

    }

    function deleteFormacionAcademica(){

        $result = ["error" => ""];

        $data['id_postulante'] = isset($_POST['id_postulante']) ? $_POST['id_postulante'] : 0;
        $data['nro_detalle'] = isset($_POST['nro_detalle']) ? $_POST['nro_detalle'] : 0;

        //validacion de datos en el backend
        if ($data['id_postulante'] === 0) $result["error"] = "El id del postulante es requerido";
        else if ($data['nro_detalle'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        if ($result["error"] === "") {
            $result = $this->postulanteRepository->deleteFormacionAcademica($data);
        }

        return $result;
    }


    function deleteExperienciaLaboral(){

        $result = ["error" => ""];

        $data['id_postulacion'] = isset($_POST['id_postulacion']) ? $_POST['id_postulacion'] : 0;
        $data['nro_detalle'] = isset($_POST['nro_detalle']) ? $_POST['nro_detalle'] : 0;

        //validacion de datos en el backend
        if ($data['id_postulacion'] === 0) $result["error"] = "El id de la postulación es requerido";
        else if ($data['nro_detalle'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        if ($result["error"] === "") {
            $result = $this->postulanteRepository->deleteExperienciaLaboral($data);
        }

        return $result;
    }

    function deleteReferencias(){

        $result = ["error" => ""];

        $data['id_postulacion'] = isset($_POST['id_postulacion']) ? $_POST['id_postulacion'] : 0;
        $data['nro_detalle'] = isset($_POST['nro_detalle']) ? $_POST['nro_detalle'] : 0;

        //validacion de datos en el backend
        if ($data['id_postulacion'] === 0) $result["error"] = "El id de la postulación es requerido";
        else if ($data['nro_detalle'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        if ($result["error"] === "") {
            $result = $this->postulanteRepository->deleteReferencias($data);
        }

        return $result;
    }

    function deleteConocimientos(){

        $result = ["error" => ""];

        $data['id_postulacion'] = isset($_POST['id_postulacion']) ? $_POST['id_postulacion'] : 0;
        $data['nro_detalle'] = isset($_POST['nro_detalle']) ? $_POST['nro_detalle'] : 0;
        $data['tipo'] = isset($_POST['tipo']) ? $_POST['tipo'] : 0;

        //validacion de datos en el backend
        if ($data['id_postulacion'] === 0) $result["error"] = "El id de la postulación es requerido";
        else if ($data['nro_detalle'] === 0) $result["error"] = "El id de la convocatoria es requerido";
        else if ($data['tipo'] === 0) $result["error"] = "El tipo de conocimiento es requerido";

        if ($result["error"] === "") {
            $result = $this->postulanteRepository->deleteConocimientos($data);
        }

        return $result;
    }

    function deletePublicaciones(){

        $result = ["error" => ""];

        $data['id_postulacion'] = isset($_POST['id_postulacion']) ? $_POST['id_postulacion'] : 0;
        $data['nro_detalle'] = isset($_POST['nro_detalle']) ? $_POST['nro_detalle'] : 0;

        //validacion de datos en el backend
        if ($data['id_postulacion'] === 0) $result["error"] = "El id de la postulación es requerido";
        else if ($data['nro_detalle'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        if ($result["error"] === "") {
            $result = $this->postulanteRepository->deletePublicaciones($data);
        }
        return $result;
    }

    function deleteExposiciones(){

        $result = ["error" => ""];

        $data['id_postulacion'] = isset($_POST['id_postulacion']) ? $_POST['id_postulacion'] : 0;
        $data['nro_detalle'] = isset($_POST['nro_detalle']) ? $_POST['nro_detalle'] : 0;

        //validacion de datos en el backend
        if ($data['id_postulacion'] === 0) $result["error"] = "El id de la postulación es requerido";
        else if ($data['nro_detalle'] === 0) $result["error"] = "El id de la convocatoria es requerido";

        if ($result["error"] === "") {
            $result = $this->postulanteRepository->deleteExposiciones($data);
        }

        return $result;
    }

}
























