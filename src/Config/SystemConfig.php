<?php declare(strict_types=1);

namespace DDT\Config;

class SystemConfig extends BaseConfig
{
    private $extensions;
	private $projects;
	
    public function __construct(string $path)
    {
        try{
            parent::__construct($path);
        }catch(\DDT\Exceptions\Config\ConfigInvalidException $e){
			// FIXME: I don't think this should die here, throwing the exception is enough
			// do this instead: $e->setPath($path); and let the calling tool handle displaying the error
            die(\DDT\Text::box("The config file was invalid, it could not be decoded: '$path'", "white", "red"));
        }
    }

	public function getDescription(): string
	{
		return $this->getKey('description');
	}

	public function getDefaultFilename(): string
	{
		return '.ddt-system.json';
	}

    public function setToolsPath(string $path): void
	{
        $this->setKey('path.tools', $path);
    }
    
    public function getToolsPath(string $subpath = null): string
	{
		$path = $this->getKey("path.tools");
		$path = $path ?: dirname(__DIR__);

		return $path . $subpath;
	}

	public function setProjectPath(string $path): void
	{
		$this->setKey('path.projects', $path);
	}

	public function getProjectPath(string $subpath = null): string
	{
		$path = $this->getKey('path.projects');
		$path = $path ?: dirname($this->getToolsPath());

		return $path . $subpath;
	}

    public function listExtensions(): array
	{
		return $this->getKey("extensions");
	}

	public function addExtension(string $name, string $url, string $path): bool
	{
		$data = [
			"url" => $url,
			"path" => $path,
		];

		$this->setKey("extensions.$name", $data);

		return count(array_diff($this->getKey("extensions.$name"), $data)) === 0;
	}

	public function removeExtension(string $name): bool
	{
		return $this->deleteKey("extensions.$name");
    }

    public function addProject(string $name, string $git, string $branch): bool
	{
        $this->setKey("projects.$name", [
			"git" => $git,
			"branch" => $branch
		]);

		return $this->hasProject($name);
	}

	public function removeProject(string $name): bool
	{
        $this->deleteKey("projects.$name");

		return $this->hasProject($name) === false;
	}

	public function hasProject($name): bool
	{
        return $this->getKey("projects.$name") !== null;
	}
}