<?php
require_once 'controladores/C_Menu.php';

// Obtener el menú filtrado por permisos del usuario actual
$menuController = new C_Menu();
$menu = $menuController->getMenuFiltradoPorPermiso();

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Mi Aplicación</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php foreach ($menu as $option): ?>
                    <li class="nav-item <?php echo !empty($option['submenus']) ? 'dropdown' : ''; ?>">
                        <a 
                            class="nav-link <?php echo !empty($option['submenus']) ? 'dropdown-toggle' : ''; ?>" 
                            href="<?php echo empty($option['submenus']) ? $option['url'] : '#'; ?>" 
                            <?php echo !empty($option['submenus']) ? 'data-bs-toggle="dropdown"' : ''; ?>
                        >
                            <?php echo htmlspecialchars($option['label']); ?>
                        </a>
                        
                        <?php if (!empty($option['submenus'])): ?>
                            <ul class="dropdown-menu">
                                <?php foreach ($option['submenus'] as $submenu): ?>
                                    <li>
                                        <a 
                                            class="dropdown-item" 
                                            href="<?php echo empty($submenu['action']) ? $submenu['url'] : '#'; ?>"
                                            <?php echo !empty($submenu['action']) ? $submenu['action'] : ''; ?>
                                            <?php if (!empty($submenu['action'])): ?>
                                                onclick="event.preventDefault(); <?php echo $submenu['action']; ?>"
                                            <?php endif; ?>
                                        >
                                            <?php echo htmlspecialchars($submenu['label']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
