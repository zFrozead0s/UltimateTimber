<?php


namespace CortexPE\std;


use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;

final class StringListValueArgument extends StringEnumArgument {
	/** @var string[] */
	private $allowedValues;

	public function __construct(string $name, array $allowedValues, bool $optional = false) {
		$this->allowedValues = $allowedValues;
		parent::__construct($name, $optional);
	}

	public function parse(string $argument, CommandSender $sender): mixed {
		return $argument;
	}
	
	public function getEnumName(): string {
		return "string";
    }

	public function getEnumValues(): array {
		return $this->allowedValues;
	}

	public function canParse(string $testString, CommandSender $sender): bool {
		return (bool)preg_match(
			"/^(" . implode("|", $this->getEnumValues()) . ")$/u",
			$testString
		);
	}

	public function getTypeName(): string {
		return "string";
	}
}