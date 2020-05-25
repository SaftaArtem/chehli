<?php

namespace Service;


class Main
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getTermNameId($termNames)
    {
        $termNames = $this->filter($termNames);
        $names = implode(',', $termNames);
        $termNamesSql = "SELECT * FROM `wp_terms` where `name` in ($names)";
        $parentTerms = $this->connection->query($termNamesSql)->fetch_all();
        $termIdName = [];
        foreach ($parentTerms as $term) {
            $termIdName[$term[1]] = $term[0];
        }
        return $termIdName;
    }

    protected function filter($arr)
    {
        $new = [];
        foreach ($arr as $item) {
            $new[] = "'" . $item . "'";
        }
        return $new;
    }

    public function getTermSql($categories)
    {
        $terms = $this->getAllTerms($categories);
        foreach ($terms as $term) {
            $newValues[] = "('" . $term . "', '$term'" . ")";
        }
        $newValues = implode(',', $newValues);

        $sql = "INSERT INTO `wp_terms`
                    (`name`, `slug`)
                    VALUES $newValues
                    ON DUPLICATE KEY UPDATE `name`= VALUES(name)";

        return $sql;
    }


    public function getTermRelationSql($categories)
    {
        $terms = $this->getAllTerms($categories);
        $children = $this->getAllChildrens($categories);
        $childrenNameId = $this->getTermNameId($children);
        $parents = array_keys($categories);
        $parentsNameId = $this->getTermNameId($parents);
        $newValues = [];
        foreach ($terms as $term) {
            $parentId = null;
            if (array_key_exists($term, $parentsNameId)) {
                $parentId = 0;
                $termId = $parentsNameId[$term];
            } elseif (array_key_exists($term, $childrenNameId)) {
                $parentName = $this->getParentName($categories, $term);
                $termId = $childrenNameId[$term];
                $parentId = $parentsNameId[$parentName];
            }
            if (!is_null($parentId)) {
                $newValues[] = "('" . $termId . "', 'product_cat'" . ",'" . $parentId ."')";
            }
        }

        $newValues = implode(',', $newValues);

        $sql = "INSERT INTO `wp_term_taxonomy`
                    (`term_id`, `taxonomy`, `parent`)
                    VALUES $newValues
                    ON DUPLICATE KEY UPDATE `term_id` = VALUES(term_id)";

        return $sql;
    }

    public function getAllTerms($categories)
    {
        $terms = [];
        foreach ($categories as $parent => $children) {
            $terms[] = $parent;
            foreach ($children as $term) {
                $term = trim($term);
                $terms[] = $term;
            }
        }
        return $terms;
    }

    public function getParentName($categories, $childName){
        foreach ($categories as $parent => $children) {
            foreach ($children as $child) {
                $child = trim($child);
                if ($child == $childName) return $parent;
            }
        }
    }

    public function getAllChildrens($categories)
    {
        $terms = [];
        foreach ($categories as $parent => $children) {
            foreach ($children as $term) {
                $term = trim($term);
                $terms[] = $term;
            }
        }
        return $terms;
    }

}