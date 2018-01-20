<?php
  class SetNode {
    public $data, $left, $right;
    public function __construct($data) {
      $this->data = $data;
      $this->left = null;
      $this->right = null;
    }
  }

  /**
   * Implementation of set using binary tree
   */
  class Set{
    private $root;
    public function __construct() {
      $this->root = null;
    }

    private function __add(&$data, &$node) {
      if($node === null) {
        $node = new SetNode($data);
        return true;
      }
      if($node->data < $data)
        return $this->__add($data, $node->right);
      if($node->data > $data)
        return $this->__add($data, $node->left);
      return false;
    }

    public function add($data) {
      return $this->__add($data, $this->root);
    }

    private function __contains(&$data, &$node) {
      if($node === null)
        return false;
      if($node->data < $data)
        return $this->__contains($data, $node->right);
      if($node->data > $data)
        return $this->__contains($data, $node->left);
      return true;
    }

    public function contains($data) {
      return $this->__contains($data, $this->root);
    }

    private function &__findFreeSpace(&$data, &$node) {
      if($node === null)
        return $node;

      if($node->data < $data)
        return $this->__findFreeSpace($data, $node->right);
      if($node->data > $data)
        return $this->__findFreeSpace($data, $node->left);
      return null;
    }

    private function __pop(&$data, &$node) {
      if($node === null)
        return false;
      if($node->data < $data)
        return $this->__pop($data, $node->right);
      if($node->data > $data)
        return $this->__pop($data, $node->left);

      if($node->right === null)
        $node = $node->left;
      else if($node->left === null)
        $node = $node->right;
      else {
        $tmp = $node->left;
        $node = $node->right;
        $freeNode = &$this->__findFreeSpace($tmp->data, $this->root);
        $freeNode = $tmp;
      }
      return true;
    }
  }
?>
