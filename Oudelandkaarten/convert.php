<?php
/* *
 *	Bixie Printshop
 *  convert.php
 *	Created on 17-6-2015 12:59
 *  
 *  @author Matthijs
 *  @copyright Copyright (C)2015 Bixie.nl
 *
 */
 
$output = array('items'=>array());
$data = json_decode(file_get_contents('landkaarten.json'));
$prijsprokey = '550d3c8a-95dc-48c5-9d7e-1834538f8ce8';
$aantalkey = '211fa528-be85-4be2-8414-c6679903c314';
$variationskey = '7a66befd-bfd4-4cfc-8345-accad716379f';
echo '<pre>';
$f = 0;
foreach ($data->items as $alias => $item) {
	print_r($item->elements->$prijsprokey->data);
	$prijs = $item->elements->$prijsprokey->data->{0}->value;

	$item->elements->$prijsprokey->data = new stdClass();

	$item->elements->$variationskey = new Variations($prijs);
	$item->elements->$aantalkey = new Aantal();
	$output['items'][$alias] = $item;
	if (count($output['items']) == 500) {
		file_put_contents('landkaarten-out'.$f.'.json', json_encode($output));
		$output = array('items'=>array());
		$f++;
	}
}
file_put_contents('landkaarten-out'.$f.'.json', json_encode($output));


class Variations {
	public $type = 'variations';
	public $name = 'Type lijst';
	public $data = array();

	function __construct ($prijs) {
		$empty = new stdClass();
		$empty->value = '';
		$basisPrijs = new stdClass();
		$basisPrijs->value = $prijs;
		$basisPrijs->tax_class = '1';
		$inscrPrijs = new stdClass();
		$inscrPrijs->value = (string) $prijs + 10.33;
		$inscrPrijs->tax_class = '1';

		$var1 = new stdClass();
		$var1->default = 1;
		$var1->attributes = array(
			'lijsttype' => 'mahoniehout',
			'inscriptie' => 'geen-inscriptie'
		);
		$var1->{'550d3c8a-95dc-48c5-9d7e-1834538f8ce8'} = array($basisPrijs);
		$var1->{'211fa528-be85-4be2-8414-c6679903c314'} = array($empty);
		$this->data[] = $var1;

		$var2 = new stdClass();
		$var2->attributes = array(
			'lijsttype' => 'wortelhout',
			'inscriptie' => 'geen-inscriptie'
		);
		$var2->{'550d3c8a-95dc-48c5-9d7e-1834538f8ce8'} = array($basisPrijs);
		$var2->{'211fa528-be85-4be2-8414-c6679903c314'} = array($empty);
		$this->data[] = $var2;

		$var3 = new stdClass();
		$var3->attributes = array(
			'lijsttype' => 'wortelhout',
			'inscriptie' => 'met-inscriptie'
		);
		$var3->{'550d3c8a-95dc-48c5-9d7e-1834538f8ce8'} = array($inscrPrijs);
		$var3->{'211fa528-be85-4be2-8414-c6679903c314'} = array($empty);
		$this->data[] = $var3;

		$var4 = new stdClass();
		$var4->attributes = array(
			'lijsttype' => 'mahoniehout',
			'inscriptie' => 'met-inscriptie'
		);
		$var4->{'550d3c8a-95dc-48c5-9d7e-1834538f8ce8'} = array($inscrPrijs);
		$var4->{'211fa528-be85-4be2-8414-c6679903c314'} = array($empty);
		$this->data[] = $var4;

	}

}

class Aantal {
	public $type = 'Quantity';
	public $name = 'Aantal';
	public $data = '';

	function __construct () {
		$this->data = new stdClass();
	}

}

/*
{
							"default": "1",
							"attributes":  {
								"lijsttype": "mahoniehout",
								"inscriptie": "geen-inscriptie"
							},
							"":  {
								"0":  {
									"value": "123.14",
									"tax_class": "1"
								}
							},
							"211fa528-be85-4be2-8414-c6679903c314":  {
								"value": ""
							}
						}




"7a66befd-bfd4-4cfc-8345-accad716379f":  {
"type": "variations",
				"name": "Type lijst",
				"data":  {
	"0":  {
		"default": "1",
						"attributes":  {
			"lijsttype": "mahoniehout",
							"inscriptie": "geen-inscriptie"
						},
						"550d3c8a-95dc-48c5-9d7e-1834538f8ce8":  {
			"0":  {
				"value": "123.14",
								"tax_class": "1"
							}
						},
						"211fa528-be85-4be2-8414-c6679903c314":  {
			"value": ""
						}
					},
					"1":  {
		"attributes":  {
			"lijsttype": "wortelhout",
							"inscriptie": "geen-inscriptie"
						},
						"550d3c8a-95dc-48c5-9d7e-1834538f8ce8":  {
			"0":  {
				"value": "123.14",
								"tax_class": "1"
							}
						},
						"211fa528-be85-4be2-8414-c6679903c314":  {
			"value": ""
						}
					},
					"2":  {
		"attributes":  {
			"lijsttype": "mahoniehout",
							"inscriptie": "met-inscriptie"
						},
						"550d3c8a-95dc-48c5-9d7e-1834538f8ce8":  {
			"0":  {
				"value": "135.64",
								"tax_class": "1"
							}
						},
						"211fa528-be85-4be2-8414-c6679903c314":  {
			"value": ""
						}
					},
					"3":  {
		"attributes":  {
			"lijsttype": "wortelhout",
							"inscriptie": "met-inscriptie"
						},
						"550d3c8a-95dc-48c5-9d7e-1834538f8ce8":  {
			"0":  {
				"value": "135.64",
								"tax_class": "1"
							}
						},
						"211fa528-be85-4be2-8414-c6679903c314":  {
			"value": ""
						}
					}
				}
			},
			"211fa528-be85-4be2-8414-c6679903c314":  {
"type": "quantity",
				"name": "Aantal",
				"data":  {

}
			},
			"550d3c8a-95dc-48c5-9d7e-1834538f8ce8":  {
"type": "pricepro",
				"name": "Prijs",
				"data":  {
	"0":  {

	}
				}
			},
			"eeba8ec7-08e7-40fe-8912-ddcbf49b2501":  {
"type": "addtocart",
				"name": "Bestel direct",
				"data":  {
	"value": "1"
				}
			},


*/