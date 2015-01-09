<?php

namespace FDevs\BowerPhpBundle\Config;

use Bowerphp\Config\Config as BaseConfig;
use Bowerphp\Package\PackageInterface;
use Bowerphp\Util\Filesystem;
use Bowerphp\Util\Json;
use RuntimeException;

/**
 * Config
 */
class Config extends BaseConfig
{
    private $bowerFile = 'bower.json';

    /**
     * init
     *
     * @param Filesystem $filesystem
     * @param string     $cacheDir
     * @param string     $installDir
     */
    public function __construct(Filesystem $filesystem, $cacheDir = '', $installDir = '', $bowerFile = '')
    {
        parent::__construct($filesystem);
        $this->cacheDir = $cacheDir ?: $this->cacheDir;
        $this->installDir = $installDir ?: $this->installDir;
        $this->bowerFile = $bowerFile ?: getcwd().'/'.$this->bowerFile;
    }

    /**
     * @return string
     */
    public function getBowerFile()
    {
        return $this->bowerFile;
    }

    /**
     * {@inheritdoc}
     */
    public function initBowerJsonFile(array $params)
    {
        $json = Json::encode([
                'name' => $params['name'],
                'authors' => [
                    $params['author'],
                ],
                'private' => true,
                'dependencies' => new \StdClass(),
            ]
        );

        return $this->filesystem->write($this->bowerFile, $json);
    }

    /**
     * {@inheritdoc}
     */
    public function updateBowerJsonFile(PackageInterface $package)
    {
        if (!$this->isSaveToBowerJsonFile()) {
            return 0;
        }

        $decode = $this->getBowerFileContent();
        $decode['dependencies'][$package->getName()] = $package->getRequiredVersion();
        $json = Json::encode($decode);

        return $this->filesystem->write($this->bowerFile, $json);
    }

    /**
     * {@inheritdoc}
     */
    public function updateBowerJsonFile2(array $old, array $new)
    {
        $json = Json::encode(array_merge($old, $new));

        return $this->filesystem->write($this->bowerFile, $json);
    }

    /**
     * {@inheritdoc}
     */
    public function getBowerFileContent()
    {
        if (!$this->bowerFileExists()) {
            throw new RuntimeException('No '.$this->bowerFile.' found. You can run "init" command to create it.');
        }
        $bowerJson = $this->filesystem->read($this->bowerFile);
        if (empty($bowerJson) || !is_array($decode = json_decode($bowerJson, true))) {
            throw new RuntimeException(sprintf('Malformed JSON %s.', $bowerJson));
        }

        return $decode;
    }

    /**
     * {@inheritdoc}
     */
    public function bowerFileExists()
    {
        return $this->filesystem->exists($this->bowerFile);
    }
}
