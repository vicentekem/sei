
<script id="template" type="x-tmpl-mustache">
    <option value="" >[ Seleccione ]</option>
    {{#data}}
        <option value="{{id}}">{{desc}}</option>
    {{/data}}
</script>

<script id="datos_personales_template" type="x-tmpl-mustache">
    {{#data}}
        <tr class="text-center">
            <td>{{tipo_doc_adjunto}}</td>
            <td>{{fecha_carga_doc}}</td>
            <td>
                <a href="{{sustento_doc}}" target="_blank" > <span class="fa fa-file text-danger"></span></a>
            </td>

        </tr>
    {{/data}}
</script>


<script id="esp_template" type="x-tmpl-mustache">
    {{#data}}
        <tr class="text-center">
            <td>{{centro_estudios}}</td>
            <td>{{tema}}</td>
            <td>{{inicio}}</td>
            <td>{{fin}}</td>
            <td>
                <a href="{{sustento}}" target="_blank" > <span class="fa fa-file text-danger"></span></a>
            </td>
            <td>
                <a href="#" onclick="deleteConocimiento(1,{{id_postulacion}},{{nro_detalle}})"> <span class="fa fa-trash text-danger"></span> </a>
            </td>
        </tr>
    {{/data}}
</script>

















