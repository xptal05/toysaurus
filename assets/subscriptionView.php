<?php
// <link rel="stylesheet" href="'.BASE_DIR.'/style/subscriptionView.css">

class Subscription
{
    public string $type;
    public array $price;
    public array $benefits;

    public function __construct($type, $price, $benefits)
    {
        $this->type = $type;
        $this->price = $price;
        $this->benefits = $benefits;
    }

    // Generate getter and setter methods dynamically
    public function __call($method, $arguments)
    {
        // Check if the method starts with 'get' or 'set'
        if (preg_match('/^(get|set)([A-Z][a-zA-Z0-9]*)$/', $method, $matches)) {
            $property = lcfirst($matches[2]); // Convert first letter to lowercase to match property name

            if (property_exists($this, $property)) {
                if ($matches[1] === 'get') {
                    return $this->$property;
                } elseif ($matches[1] === 'set') {
                    $this->$property = $arguments[0];
                    return $this; // Allow method chaining
                }
            }
        }
        throw new Exception("Method $method does not exist");
    }
}

$subscriptionMonth = new Subscription(
    "měsíční členství",
    ["amount" => 250, "currency" => ",-Kč", "period" => "měsíc"],
    [
        "description" => "Skvělá volba pro vyzkoušení Toysaurus knihovny hraček",
        "list" => [
            "10 Toysaurus bodů",
            "Půjčení malých a středních hraček ihned",
            "Půjčení velkých a největších hraček po 3. měsíci členství"
        ]
    ]
);

$subscriptionHalfYear = new Subscription(
    "půlroční členství",
    ["amount" => 1430, "currency" => ",-Kč", "period" => "půlrok"],
    [
        "description" => "Pro ty, kdo nechtějí myslet na měsíční platby příspěvků",
        "list" => [
            "10 Toysaurus bodů",
            "Půjčení malých a středních hraček ihned",
            "Půjčení velkých a největších hraček po 1. měsíci členství"
        ],
        "badge" => "půl měsíce zdarma"
    ]
);

$subscriptionYear = new Subscription(
    "Roční členství",
    ["amount" => 2750, "currency" => ",-Kč", "period" => "rok"],
    [
        "description" => "Když se opravdu chcete zapojit do dění knihovny hraček",
        "list" => [
            "10 Toysaurus bodů",
            "Půjčení všech typů hraček ihned",
            "Snadné placení členství (pouze jednou ročně"
        ],
        "badge" => "1 měsíc zdarma"
    ]
);

$subscriptions = [$subscriptionMonth, $subscriptionHalfYear, $subscriptionYear];
?>

<div class="subscriptions-container">
    <?php
    foreach ($subscriptions as $subscription) {
    ?>
        <div class="subscription-container">
            <?php 
            $badge = isset($subscription->getBenefits()['badge']) ? '<div class="container-badge">'.$subscription->getBenefits()['badge'].'</div>' : "";
            echo $badge;?>
            <div class="container-header"><?= $subscription->getType() ?></div>
            <div class="subscription-price-section">
                <div class="amount"><?= $subscription->getPrice()['amount'] ?></div>
                <div class="currency"><?= $subscription->getPrice()['currency'] ?>/</div>
                <div class="period"><?= $subscription->getPrice()['period'] ?></div>
            </div>
            <div class="subscription-description-section"><?= $subscription->getBenefits()['description'] ?></div>
            <div class="subscription-list-section">
                <ul>
                    <?php
                    $list = $subscription->getBenefits()['list'];
                    foreach ($list as $item) {
                        echo '<li>' . $item . '</li>';
                    }
                    ?>

                </ul>
            </div>
        </div>
    <?php
    } ?>
</div>