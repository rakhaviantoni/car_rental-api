<?php

class CarTest extends TestCase
{
    /**
     * /cars [GET]
     */
    public function testShouldReturnAllCars(){

        $this->get("cars", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'registration_no',
                    'color',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ],
            'meta' => [
                '*' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ]
            ]
        ]);
        
    }

    /**
     * /cars/id [GET]
     */
    public function testShouldReturnCar(){
        $this->get("cars/BB-3421", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'registration_no',
                    'color',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]    
        );
        
    }

    /**
     * /cars [POST]
     */
    public function testShouldCreateCar(){

        $parameters = [
            'registration_no' => 'MB-5375',
            'color' => 'grey',
        ];

        $this->post("cars", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'registration_no',
                    'color',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]    
        );
        
    }
    
    /**
     * /cars/id [PUT]
     */
    public function testShouldUpdateCar(){

        $parameters = [
            'registration_no' => 'MB-5375',
            'color' => 'grey',
        ];

        $this->put("cars/MB-5375", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'registration_no',
                    'color',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]    
        );
    }

    /**
     * /cars/id [DELETE]
     */
    public function testShouldDeleteCar(){
        
        $this->delete("cars/MB-5375", [], []);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
                'status',
                'message'
        ]);
    }

}