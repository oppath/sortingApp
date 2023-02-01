<?php
namespace Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Sorting Class for Translations.
 *
 * @author Santhosh K Nair <santhoshoppath@gmail.com>
 */
class SortingTranslation extends Command
{
    /**
     * Configure app commands, description & argumernt.
     *
     * @return void
     */
    public function configure(): void
    {
        $this->setName('sort')
            ->setDescription('Sort the translation property file.')
            ->setHelp('This command allows you to sort the translation file on ascending order.')
            ->addArgument('input_file_path', InputArgument::REQUIRED, 'Sort input put file path.');
    }

    /**
     * This function to execute app commands.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        //Checking whether properties file is exist and command is sorting.
        if (file_exists($input->getArgument('input_file_path')) && $input->getArgument('command') == "sort") {
            return $this->processData($input, $output);
        }
        $output->writeln('Wrong command/Wrong input file path.');
        return Command::FAILURE;
    }

    /**
     * Process and sort data
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    public function processData(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('*** Sorting Process Started ***');
        $translatedData = explode(PHP_EOL, file_get_contents($input->getArgument('input_file_path')));
        if (empty($translatedData)) {
            $output->writeln('Translation file is empty.Sorting process terminated.');
            return Command::FAILURE;
        }
        return $this->prepareData($input, $output, $translatedData);
    }

    /**
     * Prepare translation data for sort.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $translatedData
     * @return integer
     */
    public function prepareData(InputInterface $input, OutputInterface $output, $translatedData): int
    {
        $translatedArr =  [];
        $cmdString = "";
        //prepare array for sorting
        foreach ($translatedData as $translateValue) {
            if ($this->isComment($translateValue)) {
                $cmdString .= $translateValue;
            } else {
                $translatedArr[$translateValue] = $cmdString;
                $cmdString = "";
            }
        }

        if (empty($translatedArr)) {
            $output->writeln('Translated array is empty.Sorting process terminated.');
            return Command::FAILURE;
        }
        return $this->sort($input, $output, $translatedArr);
    }

    /**
     * This function is checking whether string is commented.
     *
     * @param string $translateValue
     * @return boolean
     */
    public function isComment($translateValue): bool
    {
        return (substr(trim($translateValue), 0, 1) == "#") ? true : false;
    }
    
    /**
     * Sort the prepaired data
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $translatedArr
     * @return integer
     */
    public function sort(InputInterface $input, OutputInterface $output, $translatedArr): int
    {
        try {
            $progressBar = new ProgressBar($output, count($translatedArr));
            $progressBar->start();
            //Sorting the prepared array
            ksort($translatedArr);
            $pathinfo = pathinfo($input->getArgument('input_file_path'));
            $sortFilename = $pathinfo['dirname']."/".$pathinfo['filename']."_sort.".$pathinfo['extension'];
            $sortfile = fopen($sortFilename, "w") or $output->writeln("Unable to open file!");
            foreach ($translatedArr as $key => $sortValue) {
                $progressBar->advance();
                fwrite($sortfile, $sortValue);
                fwrite($sortfile, $key);
            }
            fclose($sortfile);
            $progressBar->finish();
            $output->writeln('');
            return $this->isSortSuccess($sortFilename, $output);
        } catch (RuntimeException $e) {
            $output->writeln('Error : '. $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Check whether sorting is success or fail.
     *
     * @param string $sortFilename
     * @param OutputInterface $output
     * @return integer
     */
    public function isSortSuccess($sortFilename, OutputInterface $output): int
    {
        if (file_exists($sortFilename) && filesize($sortFilename)) {
            $output->writeln(' Sort file path is => '. $sortFilename);
            $output->writeln('*** Sorting Process Finished ***');
            return Command::SUCCESS;
        }
        $output->writeln('Something went wrong, please try again.');
        return Command::FAILURE;
    }
}
