<?php

class Menu
{
    public $menu;

    public function __construct($menu)
    {
        $this->menu = $menu;
    }
    public function view()
    {
        $menu = $this->wp_get_nav_menu_items_tree();
        if (!empty($menu)) :
            foreach ($menu as $item) :
                $class_li = (in_array("current-menu-item", $item->classes)) ? "active" : "";
                $class_li .= ($item->children) ? " menu-item-has-children" : "";
                $icon_down = ($item->children) ? '<i class="fas fa-angle-down"></i>' : '';
        ?>
                <li idpage="<?= $item->object_id; ?>" class="nav-item <?= $class_li; ?>">

                    <a class="nav-link " href="<?= esc_url($item->url); ?>"><?= $item->title ?> <?= $icon_down; ?></a>

                    <?php
                    if ($item->children) :
                    ?>
                        <ul class="sub-menu">
                            <?php
                            foreach ($item->children as $child) :
                                $active_child = (in_array("current-menu-item", $child->classes)) ? "active" : "";
                            ?>
                                <li class="nav-item" <?= $active_child; ?>>
                                    <a class="nav-link" href="<?= esc_url($child->url); ?>"><?= $child->title; ?></a>
                                </li>
                            <?php
                            endforeach;
                            ?>


                        </ul>
                    <?php
                    endif;
                    ?>
                </li>
<?php
            endforeach;
        endif;
    }

    public function wp_get_nav_menu_items_tree()
    {
        $items = wp_get_nav_menu_items($this->menu);
        if (empty($items)) return null;
        _wp_menu_item_classes_by_context($items);

        return (!empty($items)) ? $this->build_tree($items, 0) : null;
    }

    public function build_tree(array &$elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as &$element) {
            if ($element->menu_item_parent == $parentId) {
                $children = $this->build_tree($elements, $element->ID);
                if ($children)
                    $element->children = $children;

                $branch[$element->ID] = $element;
                unset($element);
            }
        }
        return $branch;
    }
}
