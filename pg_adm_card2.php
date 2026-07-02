<?php

echo"
          <div class='card'>
        <div class='card-header'>
          <h3 class='card-title'> Quadro de acompanhamento</h3>
          <div class='card-tools'>
          </div>
        </div>
        <div class='card-body'>
          <div class='col-md-12'>
            <div class='row'>
              <!-- /.col -->
              <div class='col-md-12'>
                <p class='text-center'>
                  <strong>Cursos Colaboradores</strong>
                </p>

                ";
                include 'pg_adm_card_total_percentual_colaborador.php';
              echo"</div>
              <!-- /.col -->
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <!-- /.card-footer-->
      </div>

";

?>