<?php

/** @var array $context */

function faqSchemaFromArray(array $input)
{

    $entities = [];
    foreach($input as $item) {
        $entities[] = [
            "@type" => "Question",
            "name" => $item['question'],
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => $item['answer'],
            ]
        ];
    };

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "FAQPage",
        "mainEntity" => $entities,
    ];

    return $schema;
}

function generateFaqSchema(array $data)
{

    $faqSchema = [];

    foreach($data as $index => $item) {
        $faqSchema[] = [
            'question' => $item['question'],
            'answer' => $item['answer'],
        ];

    }
    // data passed in as assoc array with keys "question" and "answer"
    // restructure to JSON-LD format so we can just encode
    $schemaJson = json_encode(faqSchemaFromArray($faqSchema));

    $faqSchema = "
	<script type=\"application/ld+json\">
		$schemaJson
	</script>
	";

    return $faqSchema;
}

$context['id'] = 'faq-' . $block['id'];
// Load values and assign defaults.
// $delay = empty(get_field('delay')) ? 5000 : get_field('delay');
$context['titleIconDisabled'] = get_field('title_icon_disabled') ?? false;
$context['titleIconPosition'] = get_field('icon_position') ?? 'right';
$context['schemaHtml'] = generateFaqSchema(get_field('items'));

class testingVars
{
    public function localScope()
    {

        $onlyone = 2;

        $values = get_defined_vars();
        var_dump($values);

    }
}

// $test = new testingVars();
// $test->localScope();
