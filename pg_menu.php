<?php

echo"
      <nav class='mt-2'>
        <ul class='nav nav-pills nav-sidebar flex-column' data-widget='treeview' role='menu' data-accordion='false'>
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class='nav-item'>
            <a href='#' class='nav-link'>
              <i class='nav-icon fas fas fa-cog'></i>
              <p>
                Configuração
                <i class='right fas fa-angle-left'></i>
              </p>
            </a>
            <ul class='nav nav-treeview'>
              <li class='nav-item'>
                <a href='gerenciar_cargo.php' class='nav-link'>
                  <i class='far fa-circle nav-icon'></i>
                  <p>Cargo</p>
                </a>
              </li>
              <li class='nav-item'>
                <a href='gerenciar_usuario.php' class='nav-link'>
                  <i class='far fa-circle nav-icon'></i>
                  <p>Usuário</p>
                </a>
              </li>
              <li class='nav-item'>
                <a href='gerenciar_tipo_treinamento.php' class='nav-link'>
                  <i class='far fa-circle nav-icon'></i>
                  <p>Tipo de treinamento</p>
                </a>
              </li>
              <li class='nav-item'>
                <a href='gerenciar_curso.php' class='nav-link'>
                  <i class='far fa-circle nav-icon'></i>
                  <p>Curso</p>
                </a>
              </li>
              <li class='nav-item'>
                <a href='gerenciar_orcamento.php' class='nav-link'>
                  <i class='far fa-circle nav-icon'></i>
                  <p>Orçamento Anual</p>
                </a>
              </li>
              <li class='nav-item'>
                <a href='gerenciar_acompanhar_treinamento.php' class='nav-link'>
                  <i class='far fa-circle nav-icon'></i>
                  <p>Acompanhar treinamento</p>
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </nav>

";

?>