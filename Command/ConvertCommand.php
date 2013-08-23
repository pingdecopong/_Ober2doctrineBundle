<?php

namespace Arte\Ober2doctrineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Dumper;
use Arte\Ober2doctrineBundle\Lib\OberMngToYaml;
use Arte\Ober2doctrineBundle\Lib\OberMng;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class ConvertCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('arte:ober2doctrine')
            ->setDescription('Convert file from OBER to Doctrine2 YAML')
            ->addArgument('BundleName', InputArgument::REQUIRED, 'bundle name')
            ->setHelp(<<<EOT
convert ober file to yaml file.

EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //
        $bundle = $this->getApplication()->getKernel()->getBundle($input->getArgument('BundleName'));
        $output->writeln(sprintf('BundleName "<info>%s</info>"', $bundle->getName()));

        $bundleName = $bundle->getName();
        $bundleNamespace = $bundle->getNamespace();
        $oberFilePath = $bundle->getPath() . '/Resources/config/ober/er.edm';
        $outputFilePath = $bundle->getPath() . '/Resources/config/doctrine';
        $mergeYmlFilePath = $bundle->getPath() . '/Resources/config/ober/merge/';
        $mergeAddYmlFilePath = $bundle->getPath() . '/Resources/config/ober/merge/add/';
        $mergeDeleteYmlFilePath = $bundle->getPath() . '/Resources/config/ober/merge/delete/';

            $fs = new Filesystem();
        if(!$fs->exists($oberFilePath)){
            $output->writeln(sprintf('"<info>%s</info>" file does not exist.', $oberFilePath));
            return;
        }

        if(!$fs->exists($outputFilePath)){
            $fs->mkdir($outputFilePath);
            $output->writeln(sprintf(' > "<info>%s</info>" make directory..', $outputFilePath));
        }

        $oberMng = new OberMng();
        $oberMng->loadFile($oberFilePath);

        $oberMngToYaml = new OberMngToYaml($oberMng, $bundleNamespace."\\Entity");
        $ret = $oberMngToYaml->convertArray();

        foreach($ret as $key => $value)
        {
            $yamlParser = new Parser();

            $deleteYamlFilePath = $mergeDeleteYmlFilePath . $key . '.yml';
            if(file_exists($deleteYamlFilePath)){
                $temp = $yamlParser->parse(file_get_contents($deleteYamlFilePath));
                $this->deleteArray($value, $temp);
            }

            $addYamlFilePath = $mergeAddYmlFilePath . $key . '.yml';
            if(file_exists($addYamlFilePath)){
                $temp = $yamlParser->parse(file_get_contents($addYamlFilePath));
                $value = array_merge_recursive($value, $temp);
            }

            $yaml = Yaml::dump($value, 10);
            $outputfile = $outputFilePath.'/'.$key.'.orm.yml';
            file_put_contents($outputfile, $yaml);
            $output->writeln(sprintf(' > generate "<info>%s</info>"', $outputfile));
        }

    }

    private function deleteArray(&$data, $deleteArray)
    {
        foreach($data as $key => $value)
        {
            if(!isset($deleteArray[$key]))
            {
                continue;
            }

            if(is_array($data[$key]))
            {
                self::deleteArray($data[$key], $deleteArray[$key]);
            }else{
                if($data[$key] === $deleteArray[$key])
                {
                    unset($data[$key]);
                }
            }
        }

    }
}
