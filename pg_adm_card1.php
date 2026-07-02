<?php

echo"
          <div class='card'>
        <div class='card-header'>
          <h3 class='card-title'> Infomações</h3>
          <div class='card-tools'>
          </div>
        </div>
        <div class='card-body'>
          <div class='col-md-12'>
            <!-- Small boxes (Stat box) -->
            <div class='row'>
              <div class='col-lg-3 col-6'>
                <!-- small box -->
                <div class='small-box bg-info'>
                  <div class='inner'>";
                  echo "
                    <h3>
                    ";
                      include 'pg_adm_card1_total_colaboradores.php';                    
                  echo"
                  </h3> 
                  <p>Total Colaboradores</p>
                  </div>
                </div>
              </div>
              <!-- ./col -->
              <div class='col-lg-3 col-6'>
                <!-- small box -->
                <div class='small-box bg-success'>
                  <div class='inner'>
                    <h3>
                      <sup style='font-size: 20px'>R$
                      </sup>";
                      include 'pg_adm_card2_total_orcamento.php'; 
                    echo "</h3>

                    <p>Orçamento anual</p>
                  </div>
                </div>
              </div>
              <!-- ./col -->
              <div class='col-lg-3 col-6'>
                <!-- small box -->
                <div class='small-box bg-warning'>
                  <div class='inner'>
                    <h3>";
                      include 'pg_adm_card3_total_treinamentos.php';
                    echo"</h3>

                    <p>Total Treinamentos</p>
                  </div>
                </div>
              </div>
              <!-- ./col -->
              <div class='col-lg-3 col-6'>
                <!-- small box -->
                <div class='small-box bg-danger'>
                  <div class='inner'>
                    <h3>";
                      include 'pg_adm_card4_orcamento_restante.php';
                    echo"</h3>

                    <p>Orçamento Restante</p>
                  </div>
                </div>
              </div>
              <!-- ./col -->
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <!-- /.card-body -->
        <!-- /.card-footer-->
      </div>

";

?>