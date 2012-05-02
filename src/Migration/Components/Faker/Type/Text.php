<?php
namespace Migration\Components\Faker\Type;

use Migration\Components\Faker\Exception as FakerException;
use Migration\Components\Faker\Utilities;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Text extends Type
{

    protected static $words = array(
                        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',
                        'Curabitur sed tortor. Integer aliquam adipiscing lacus.',
                        'Ut nec urna et arcu imperdiet ullamcorper. Duis at lacus.',
                        'Quisque purus sapien, gravida non, sollicitudin a, malesuada id,',
                        'erat. Etiam vestibulum massa rutrum magna. Cras convallis',
                        'convallis dolor. Quisque tincidunt pede ac urna. Ut tincidunt',
                        'vehicula risus. Nulla eget metus eu erat semper rutrum.',
                        'Fusce dolor quam, elementum at, egestas a,',
                        'scelerisque sed, sapien. Nunc pulvinar arcu et pede.',
                        'Nunc sed orci lobortis augue scelerisque mollis.',
                        'Phasellus libero mauris, aliquam eu, accumsan sed,',
                        'facilisis vitae, orci. Phasellus dapibus quam quis diam.',
                        'Pellentesque habitant morbi tristique senectus et netus et',
                        'malesuada fames ac turpis egestas. Fusce aliquet magna a neque.',
                        'Nullam ut nisi a odio semper cursus. Integer mollis. Integer',
                        'tincidunt aliquam arcu. Aliquam ultrices iaculis odio.',
                        'Nam interdum enim non nisi. Aenean eget metus. In nec orci.',
                        'Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus.',
                        'Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est,',
                        'mollis non, cursus non, egestas a, dui. Cras pellentesque.',
                        'Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna.',
                        'Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla.',
                        'Donec feugiat metus sit amet ante.',
                        'Vivamus non lorem vitae odio sagittis semper.',
                        'Nam tempor diam dictum sapien. Aenean massa.',
                        'Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec,',
                        'cursus a, enim. Suspendisse aliquet, sem ut cursus luctus,',
                        'ipsum leo elementum sem, vitae aliquam eros turpis non enim.',
                        'Mauris quis turpis vitae purus gravida sagittis. Duis gravida.',
                        'Praesent eu nulla at sem molestie sodales.',
                        'Mauris blandit enim consequat purus. Maecenas libero est,',
                        'congue a, aliquet vel, vulputate eu, odio. Phasellus at',
                        'augue id ante dictum cursus. Nunc mauris elit, dictum eu,',
                        'eleifend nec, malesuada ut, sem. Nulla interdum.',
                        'Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum.',
                        'Phasellus vitae mauris sit amet lorem semper auctor.',
                        'Mauris vel turpis. Aliquam adipiscing lobortis risus.',
                        'In mi pede, nonummy ut, molestie in, tempus eu, ligula.',
                        'Aenean euismod mauris eu elit. Nulla facilisi. Sed neque.',
                        'Sed eget lacus. Mauris non dui nec urna suscipit nonummy.',
                        'Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in',
                        'faucibus orci luctus et ultrices posuere cubilia Curae;',
                        'Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor',
                        'egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae,',
                        'posuere at, velit. Cras lorem lorem, luctus ut,',
                        'pellentesque eget, dictum placerat, augue. Sed molestie.',
                        'Sed id risus quis diam luctus lobortis. Class aptent taciti',
                        'sociosqu ad litora torquent per conubia nostra, per inceptos',
                        'hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus.',
                        'Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas.',
                        'Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci',
                        'tincidunt adipiscing. Mauris molestie pharetra nibh.',
                        'Aliquam ornare, libero at auctor ullamcorper,',
                        'nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu.',
                        'Morbi sit amet massa. Quisque porttitor eros nec tellus.',
                        'Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam.',
                        'Duis mi enim, condimentum eget, volutpat ornare, facilisis eget,',
                        'ipsum. Donec sollicitudin adipiscing ligula.',
                        'Aenean gravida nunc sed pede. Cum sociis natoque penatibus et',
                        'magnis dis parturient montes, nascetur ridiculus mus.',
                        'Proin vel arcu eu odio tristique pharetra.',
                        'Quisque ac libero nec ligula consectetuer rhoncus.',
                        'Nullam velit dui, semper et, lacinia vitae, sodales at, velit.',
                        'Pellentesque ultricies dignissim lacus.',
                        'Aliquam rutrum lorem ac risus. Morbi metus.',
                        'Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper',
                        'viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis,',
                        'tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla.',
                        'Integer urna. Vivamus molestie dapibus ligula.',
                        'Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas',
                        'ligula. Nullam feugiat placerat velit. Quisque varius.',
                        'Nam porttitor scelerisque neque. Nullam nisl.',
                        'Maecenas malesuada fringilla est. Mauris eu turpis.',
                        'Nulla aliquet. Proin velit. Sed malesuada augue ut lacus.',
                        'Nulla tincidunt, neque vitae semper egestas, urna justo',
                        'faucibus lectus, a sollicitudin orci sem eget massa.',
                        'Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero.',
                        'Integer in magna. Phasellus dolor elit, pellentesque a,',
                        'facilisis non, bibendum sed, est. Nunc laoreet lectus',
                        'quis massa. Mauris vestibulum, neque sed dictum eleifend,',
                        'nunc risus varius orci, in consequat enim diam vel arcu.',
                        'Curabitur ut odio vel est tempor bibendum. Donec felis orci,',
                        'adipiscing non, luctus sit amet, faucibus ut,',
                        'nulla. Cras eu tellus eu augue porttitor interdum.',
                        'Sed auctor odio a purus. Duis elementum, dui quis accumsan',
                        'convallis, ante lectus convallis est, vitae sodales nisi magna',
                        'sed dui. Fusce aliquam, enim nec tempus scelerisque,',
                        'lorem ipsum sodales purus, in molestie tortor nibh',
                        'sit amet orci. Ut sagittis lobortis mauris.',
                        'Suspendisse aliquet molestie tellus. Aenean egestas',
                        'hendrerit neque. In ornare sagittis felis. Donec tempor,',
                        'est ac mattis semper, dui lectus rutrum urna, nec luctus',
                        'felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem,',
                        'vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim,',
                        'gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque',
                        'penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
                        'Proin vel nisl. Quisque fringilla euismod enim.',
                        'Etiam gravida molestie arcu. Sed eu nibh vulputate mauris',
                        'sagittis placerat. Cras dictum ultricies ligula.',
                        'Nullam enim. Sed nulla ante, iaculis nec, eleifend non,',
                        'dapibus rutrum, justo. Praesent luctus. Curabitur egestas nunc',
                        'sed libero. Proin sed turpis nec mauris blandit mattis.',
                        'Cras eget nisi dictum augue malesuada malesuada.',
                        'Integer id magna et ipsum cursus vestibulum. Mauris magna.',
                        'Duis dignissim tempor arcu. Vestibulum ut eros non enim',
                        'commodo hendrerit. Donec porttitor tellus non magna.',
                        'Nam ligula elit, pretium et, rutrum non, hendrerit id, ante.',
                        'Nunc mauris sapien, cursus in, hendrerit consectetuer,',
                        'cursus et, magna. Praesent interdum ligula eu enim.',
                        'Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus.',
                        'Nullam suscipit, est ac facilisis facilisis,',
                        'magna tellus faucibus leo, in lobortis tellus justo sit',
                        'amet nulla. Donec non justo. Proin non massa non ante',
                        'bibendum ullamcorper. Duis cursus, diam at pretium aliquet,',
                        'metus urna convallis erat, eget tincidunt dui augue eu tellus.',
                        'Phasellus elit pede, malesuada vel, venenatis vel, faucibus id,',
                        'libero. Donec consectetuer mauris id sapien. Cras dolor dolor,',
                        'tempus non, lacinia at, iaculis quis, pede. Praesent eu dui.',
                        'Cum sociis natoque penatibus et magnis dis parturient montes,',
                        'nascetur ridiculus mus. Aenean eget magna.',
                        'Suspendisse tristique neque venenatis lacus.',
                        'Etiam bibendum fermentum metus.',
                        'Aenean sed pede nec ante blandit viverra. Donec tempus,',
                        'lorem fringilla ornare placerat, orci lacus vestibulum lorem,',
                        'sit amet ultricies sem magna nec quam. Curabitur vel lectus.',
                        'Cum sociis natoque penatibus et magnis dis parturient montes,',
                        'nascetur ridiculus mus. Donec dignissim magna a tortor.',
                        'Nunc commodo auctor velit. Aliquam nisl.',
                        'Nulla eu neque pellentesque massa lobortis ultrices.',
                        'Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet',
                        'lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu',
                        'ac orci. Ut semper pretium neque. Morbi quis urna.',
                        'Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla.',
                        'In tincidunt congue turpis. In condimentum. Donec at arcu.',
                        'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices',
                        'posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel',
                        'pede blandit congue. In scelerisque scelerisque dui.',
                        'Suspendisse ac metus vitae velit egestas lacinia. Sed congue,',
                        'elit sed consequat auctor, nunc nulla vulputate dui, nec tempus',
                        'mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae',
                        'diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat.',
                        'Sed nec metus facilisis lorem tristique aliquet.',
                        'Phasellus fermentum convallis ligula. Donec luctus aliquet odio.',
                        'Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna.',
                        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',
                        'Etiam laoreet, libero et tristique pellentesque,',
                        'tellus sem mollis dui, in sodales elit erat vitae risus.',
                        'Duis a mi fringilla mi lacinia mattis. Integer eu lacus.',
                        'Quisque imperdiet, erat nonummy ultricies ornare,',
                        'elit elit fermentum risus, at fringilla purus mauris a nunc.',
                        'In at pede. Cras vulputate velit eu sem. Pellentesque ut',
                        'ipsum ac mi eleifend egestas. Sed pharetra, felis eget',
                        'varius ultrices, mauris ipsum porta elit, a feugiat tellus',
                        'lorem eu metus. In lorem. Donec elementum, lorem ut aliquam',
                        'iaculis, lacus pede sagittis augue, eu tempor erat neque non',
                        'quam. Pellentesque habitant morbi tristique senectus et netus',
                        'et malesuada fames ac turpis egestas. Aliquam fringilla cursus',
                        'purus. Nullam scelerisque neque sed sem egestas blandit. Nam',
                        'nulla magna, malesuada vel, convallis in, cursus et, eros. Proin',
                        'ultrices. Duis volutpat nunc sit amet metus. Aliquam erat volutpat.',
                        'Nulla facilisis. Suspendisse commodo tincidunt nibh.',
                        'Phasellus nulla. Integer vulputate, risus a ultricies adipiscing,',
                        'enim mi tempor lorem, eget mollis lectus pede et risus.',
                        'Quisque libero lacus, varius et, euismod et, commodo at, libero.',
                        'Morbi accumsan laoreet ipsum. Curabitur consequat,',
                        'lectus sit amet luctus vulputate, nisi sem semper erat,',
                        'in consectetuer ipsum nunc id enim. Curabitur massa.',
                        'Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus.',
                        'Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt,',
                        'nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo',
                        'nec ante. Maecenas mi felis, adipiscing fringilla, porttitor',
                        'vulputate, posuere vulputate, lacus. Cras interdum. Nunc',
                        'sollicitudin commodo ipsum. Suspendisse non leo. Vivamus',
                        'nibh dolor, nonummy ac, feugiat non, lobortis quis, pede.',
                        'Suspendisse dui. Fusce diam nunc, ullamcorper eu, euismod ac,',
                        'fermentum vel, mauris. Integer sem elit, pharetra ut,',
                        'pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi.',
                        'Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae,',
                        'aliquet nec, imperdiet nec, leo. Morbi neque tellus,',
                        'imperdiet non, vestibulum nec, euismod in, dolor.',
                        'Fusce feugiat. Lorem ipsum dolor sit amet, consectetuer',
                        'adipiscing elit. Aliquam auctor, velit eget laoreet posuere,',
                        'enim nisl elementum purus, accumsan interdum libero dui nec ipsum.',
                );


    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $parag      = $this->getOption('paragraphs');
        $min_lines  = $this->getOption('minlines');
        $max_lines  = $this->getOption('maxlines');
        $return     = '';
        
        # generate the text
        for($i = $parag; $i > 0; $i--) {
            $return .=  $this->utilities->generateRandomText(self::$words,true,$min_lines,$max_lines).PHP_EOL;
        }
        
        return $return;
    }
    
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
       return '<datatype name="'.$this->getId().'"></datatype>' . PHP_EOL;
    }
    
    //  -------------------------------------------------------------------------

    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->scalarNode('paragraphs')
                    ->defaultValue(4)
                    ->setInfo('Text format to use')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_int($v);
                        })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('Numeric::Paragraphs must be and integer');
                        })
                    ->end()
                ->end()
                ->scalarNode('maxlines')
                    ->defaultValue(200)
                    ->setInfo('Maxium number of line per paragraph')
                    ->setExample('5 | 10 | ...')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_integer($v);
                        })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('Numeric::maxlines must be and integer');
                        })
                    ->end()
                ->end()
                ->scalarNode('minlines')
                    ->defaultValue(5)
                    ->setInfo('Minimum number of lines per paragraph')
                    ->setExample('20 | 100 | ..')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_integer($v);
                        })
                        ->then(function($v){
                            throw new \Migration\Components\Faker\Exception('Numeric::minlines must be and integer');
                        })
                    ->end()
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
    //  -------------------------------------------------------------------------

    public function merge($config)
    {
        try {
            
            $processor = new Processor();
            return $processor->processConfiguration($this, array('config' => $config));
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $this->options = $this->merge($this->options);
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */