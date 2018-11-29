<?php
namespace LaravelDay\Article;

final class Article
{
	private $title;
	private $body;
	private $creationDate
	private $id;
	
	public function __construct(int $id, string $title, string $body, \DateTime $creationDate){
		$this->title = $title;
		$this->body = $body;
		$this->creationDate = $creationDate;
		$this->id = $id
	}
	
	public function getBody():string{
		return $this->body;
	}
	
	public function getTitle():string{
		return $this->title;
	}
	
	public function getCreationDate():\DateTime{
		return $this->CreationDate;
	}
	
	public function getId():int{
		return $this->id;
	}
}