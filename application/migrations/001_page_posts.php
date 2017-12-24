<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_page_posts extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'post_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '200',
                                'unique' => TRUE
                        ),
                        'page_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '200',
                        ),
                        'title' => array(
                                'type' => 'TEXT',
                                'null' => TRUE,
                        ),
                        'description' => array(
                                'type' => 'TEXT',
                                'null' => TRUE,
                        ),
                        'image_url' => array(
                                'type' => 'VARCHAR',
                                'null' => TRUE,
                                'constraint' => '700',
                        ),
                        'likes' => array(
                                'type' => 'INT',
                                'null' => TRUE,
                                'constraint' => '5',
                        ),
                        'comments_count' => array(
                                'type' => 'INT',
                                'null' => TRUE,
                                'constraint' => '5',
                        ),
                        'published_date' => array(
                                'type' => 'DATETIME',
                                'null' => TRUE,
                                'constraint' => '5',
                        ),
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('posts');
        }

        public function down()
        {
                $this->dbforge->drop_table('posts');
        }
}