<?php

namespace App\Console\Commands;

use App\Http\Controllers\OfertaLaboralEstudianteController;
use Illuminate\Console\Command;
use App\Models\Estudiante;
use App\Models\Empleador;
use App\Models\OfertasLaborales;
use App\Models\Docente;
use App\Models\OfertaLaboralEstudiante;
use Carbon\Carbon;
//template para correo
use App\Traits\TemplateCorreo;

use function PHPUnit\Framework\isEmpty;

class NotificarUsuarios extends Command

{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $tiempoValidarFormEstudiante=48;// horas
    private $tiempoValidarFormEmpleador=72;// horas
    private $tiempoValidarOfertaLaboral=72;// horas
    private $tiempoSeleccionarPostulante=192;//horas //8 dias
    private $tiempoDePublicacionOfertaLaboralGestor=24;
    protected $signature = 'command:notificarUsuarios';
    //reutilizando el codigo con los correos
    use TemplateCorreo;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cuando pasa 48 se envia la notifiacion ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

     $this->notificarEstudiante();
     $this->notificarEmpleador();
     $this->notificarOfertaLaboralExpirada();
     $this->notificarOfertaLaboralExpiradaDePublicarGestor();
     $this->notificarSeleccionarPostulanteEmpleador();

    }

    private function notificarOfertaLaboralExpirada(){

        try {
            //selecinar todas la ofertas laborales que hayan expirado su tiemppo
            $ObjOfertaLaboral=OfertasLaborales::join("empleador","empleador.id","=","oferta_laboral.fk_empleador")
            ->join("usuario","usuario.id","empleador.fk_usuario")
            ->select("usuario.correo",
                    "empleador.nom_representante_legal",
                    "empleador.razon_empresa",
                    "oferta_laboral.*"
                    )
            ->where("oferta_laboral.estado","=","1")
            ->where("oferta_laboral.obervaciones","=","")
            ->whereDate('oferta_laboral.updated_at',"<=",Carbon::now()
            ->subHour($this->tiempoValidarOfertaLaboral))
            ->get();
            //
            $parrafoMensaje="Se le informa que la validación de su
                            oferta laboral ha expirado, por favor vuelva a
                            insistir ingresando a su cuenta y reenviando la oferta";

            $TituloCorreo="Proceso de publicacion de oferta laboral";
            $observaciones="La validación de su oferta laboral ha expirado, porfavor vuelva a insistir reenviando el formulario";
            foreach ($ObjOfertaLaboral as $key => $value) {
                //actulizamos las observacione de la oferta laboral explicando la razon
                $ofertaLaboralBoleand=OfertasLaborales::where("id","=",$value['id'])
                ->update(array( "obervaciones"=>$observaciones));
                //preparamos la plantilla html para enviar al correo
                $plantillaCorreo=$this->templateHtmlCorreo($value['nom_representante_legal'],$parrafoMensaje);
                //enviamos el corrreo
                $enviarCorreoBolean=$this->enviarCorreo( $plantillaCorreo,$value['correo'],getenv("TITULO_CORREO_OFERTA_LABORAL"));

            }

        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }
    //nofiticar al postulante que su registro ha expirado
    private function notificarEstudiante(){
        // $handle = fopen("log.txt", "a");
        try {
            $usuario=Estudiante::join("usuario","usuario.id","=","estudiante.fk_usuario")
            ->select("estudiante.*","usuario.*")
            ->where("estudiante.estado",0)
            ->where("usuario.tipoUsuario",2)
            ->where("estudiante.observaciones","")
            ->whereDate('estudiante.updated_at',"<=",
            Carbon::now()->subHour($this->tiempoValidarFormEstudiante))
            ->get();
            //
            $parrafoMensaje="Se le informa que la validación de su
                            información ha expirado, por favor vuelva a
                            insistir ingresando a su cuenta y reenviando el
                            formulario de registro";
            //
            $observaciones="La validación de su información ha expirado, por favor vuelva a insistir reenviando el formulario";
            foreach ($usuario as $key => $value) {
                $estudianteBooleand=Estudiante::where("fk_usuario","=",$value['fk_usuario'])
                ->update(array( 'estado'=>0,"observaciones"=>$observaciones));
                $plantillaCorreo=$this->templateHtmlCorreo(($value['nombre'].' '.$value['apellido']),$parrafoMensaje);
                $enviarCorreoBolean=$this->enviarCorreo( $plantillaCorreo,$value['correo'],getenv("TITULO_CORREO_POSTULANTE"));

            }
            // fclose($handle);
        } catch (\Throwable $th) {
            die($th->getMessage());
        }
    }
    //el tiempo de validacion del formulario re registro del empleador expiro
    private function notificarEmpleador(){

        try {

            $usuario=Empleador::join("usuario","usuario.id","=","empleador.fk_usuario")
            ->select("empleador.*","usuario.*")
            ->where("usuario.estado",1)
            ->where("usuario.tipoUsuario",6)
            ->where("empleador.observaciones","")
            ->whereDate('empleador.updated_at',"<=",
                Carbon::now()->subHour($this->tiempoValidarFormEmpleador))
            ->get();
            $parrafoMensaje="Se le informa que la validación de su
            información ha expirado, por favor vuelva a
            insistir ingresando a su cuenta y reenviando el
            formulario de registro";

            $observaciones="La validación de su información ha expirado, por favor vuelva a insistir reenviando el formulario";
            foreach ($usuario as $key => $value) {
                $empleadorBooleand=Empleador::join("usuario","usuario.id","empleador.fk_usuario")
                ->where("empleador.fk_usuario","=",$value['fk_usuario'])
                ->update(array( 'empleador.estado'=>0,"empleador.observaciones"=>$observaciones));

                $plantillaHtml=
                                $this->templateHtmlCorreo(
                                    $value['nom_representante_legal'],
                                    $parrafoMensaje
                                );
                $enviarCorreoBolean=$this->enviarCorreo( $plantillaHtml,$value['correo'],getenv("TITULO_CORREO_POSTULANTE"));

            }
        } catch (\Throwable $th) {
            echo $th->getMessage();

        }
    }

    //comunicar al gestor para que publique la oferta
    private function notificarOfertaLaboralExpiradaDePublicarGestor(){
        //enviar correo del registro el encargado

        try {
            //selecinar todas la ofertas laborales que hayan expirado su tiemppo de publicacion por parte del gestor
            $ObjOfertaLaboral=OfertasLaborales::join("empleador","empleador.id","=","oferta_laboral.fk_empleador")
            ->join("usuario","usuario.id","empleador.fk_usuario")
            ->select("usuario.correo",
                    "empleador.nom_representante_legal",
                    "empleador.razon_empresa",
                    "oferta_laboral.*"
                    )
            ->where("oferta_laboral.estado","=",2)
            ->whereDate('oferta_laboral.updated_at',"<=",Carbon::now()
            ->subHour($this->tiempoDePublicacionOfertaLaboralGestor))
            ->get();

            //enviamo la notificacion al gestor
            $usuarioGestor=Docente::join("usuario","usuario.id","=","docente.fk_usuario")
            ->select("docente.*","usuario.correo")
            ->where("usuario.estado",1)
            ->where("usuario.tipoUsuario",4)
            ->get();
            //recorrer todos los usuario que sean gestor
            foreach ($ObjOfertaLaboral as $key => $value) {
                $nombreOferta=$value['puesto'];
                $extnernal_of=$value['external_of'];
                $puesto=$value['puesto'];
                foreach ($usuarioGestor as $key => $value) {
                    $parrafoMensaje=" tiene pendiente publicar la  oferta ".$nombreOferta;
                    //tengo q redacatra el menaje aL ENCAGRADO
                    $plantillaHtmlCorreo=
                                    $this->templateHtmlCorreo(
                                                                $value['nombre']." ".$value['apellido'],
                                                                $parrafoMensaje
                                                            );
                    $enviarCorreoBolean=
                                        $this->enviarCorreo($plantillaHtmlCorreo,
                                                            $value['correo'],
                                                            getenv("TITULO_CORREO_PUBLICACION_OFERTA"));
                    //ACTUALIZO EL ESTADO PARA QUE SE VUELVA A REENVIAR EL ESTADO DE LA OFERTA
                    $ObjOfertaLaboralUpdate=OfertasLaborales::where("external_of","=", $extnernal_of)
                    ->update(array('estado'=>2));
                    //========================================

                }
            }

        } catch (\Throwable $th) {
            die($th->getMessage());
            return $th->getMessage();
        }
    }
    private function notificarSeleccionarPostulanteEmpleador(){
        //cuando se haya inscrito el primer postulante comienza a contabilizar los 8 dias
        //1.Buscamos cuales son los usuarios que se han escrito en una determiada oferta que esten pubicas


        try {
            //lista todoas las ofertas que este publicadas
            $ofertaLaboralPublicada=OfertasLaborales::join("empleador","empleador.id","oferta_laboral.fk_empleador")
            ->join("usuario","usuario.id","empleador.fk_usuario")
            ->select("correo",
            "razon_empresa",
            "oferta_laboral.*",
            "nom_representante_legal")
            ->where("oferta_laboral.estado",3)
            ->get();
            //2. Buscar si existen postulantes inscritos
            foreach ($ofertaLaboralPublicada as $key => $value) {
                //la oferta tiene q estar en estado publico, que busque el primero que se inscribio
                //desde el primer inscrito se contabiliza el tiempo de los 8 dias
                $postulanteEncontrado=
                OfertaLaboralEstudiante::where("fk_oferta_laboral",$value['id'])
                ->where("estado",1)
                ->whereDate('updated_at',"<=",
                Carbon::now()->subHour($this->tiempoSeleccionarPostulante))
                ->first();
                if(isset($postulanteEncontrado)){
                    $parrafoEmpleador="Se le informa que han trancurrido 8 dias desde que se inicio el proceso de postulación,
                              por lo cual su oferta laboral se encuentra deshabilitado,
                              para volver a reactivar su oferta laboral denominada <b>".$value['puesto']."</b>
                              por favor realizarlo mediante el siguiente enlace
                              <a href='".getenv("DOMINIO_WEB_REACTIVAR_OFERTA")."/reactivar-oferta-laboral/".$value["external_of"]."'>".
                                            getenv("DOMINIO_WEB_REACTIVAR_OFERTA")."/reactivar-oferta-laboral/".$value["external_of"].
                              "</a>";

                    $plantillaHtmlEmpleador= $this->templateHtmlCorreo($value['nom_representante_legal'],$parrafoEmpleador);
                    $enviarCorreoBolean=$this->enviarCorreo($plantillaHtmlEmpleador,$value['correo'],getenv("TITULO_CORREO_APLICAR_OFERTA"));

                    //cambio de estado a la oferta laboral a finalizado
                    $ofertaLaboralEstado=OfertasLaborales::where("id",$value['id'])
                    ->update(array(
                        'estado'=>4
                    ));

                    // NOTIFICAR AL ENCARGADADO PARA QUE CALIFIQUE AL EMPLEADOR
                    // NOTIFICAR AL ENCARGADADO PARA QUE CALIFIQUE AL EMPLEADOR
                    $parraEncargado="Se le informa que el señor <b>".$value['nom_representante_legal']."</b>
                             representante de la empresa <b>".$value['razon_empresa']."</b>,
                             no ha realizado ninguna contración en los 8 dias habiles
                             en la oferta laboral denominada <b>".$value['puesto']."</b>";

                    $usuarioEncargado=Docente::join("usuario","usuario.id","=","docente.fk_usuario")
                    ->select("docente.*","usuario.*")
                    ->where("usuario.estado",1)
                    ->where("usuario.tipoUsuario",5)
                    ->get();
                    foreach ($usuarioEncargado as $key => $item) {
                        $plantillaHtmlCorreoEncargado=
                            $this->templateHtmlCorreo(
                                                        $item["nombre"]." ".$item["apellido"],
                                                        $parraEncargado
                                                    );
                        $enviarCorreoBoleanEncargado=$this->enviarCorreo($plantillaHtmlCorreoEncargado,
                                                            $item['correo'],
                                                            getenv("TITULO_CORREO_APLICAR_OFERTA"));
                    }

                }
            }
        } catch (\Throwable $th) {
            die($th->getMessage());
        }


    }
}

