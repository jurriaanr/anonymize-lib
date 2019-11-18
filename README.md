# Anonymize library

Het doel is om dit te kunnen doen in een entity:
        
    use Doctrine\ORM\Mapping as ORM;
    use Oberon\Anonymize\Annotations\Anonymize;
    use Oberon\Anonymize\Annotations as Anon;
    use Oberon\Anonymize\Model\AnonymizableInterface;
    use Oberon\Anonymize\Traits\Anonymizable;
    
    /**
     * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
     * @ORM\Table(name="test")
     * @Anonymize(mode=Anonymize::AFTER_DATE, dateField="createdAt", dateInterval="P8M")
     */
    class Test implements AnonymizableInterface
    {
        use Anonymizable;
    
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue
         */
        public $id;

        /**
         * @ORM\Column(type="string")
         * @Anon\Regex(regex="[a-m]", replace="*", options="i")
         */
        public $name;

        /**
         * @ORM\Column(type="string")
         * @Anon\Mask()
         */
        public $address;

        /**
         * @ORM\Column(type="string")
         * @Anon\Mask(char="#", maskCount=8, replaceCount=4)
         */
        public $bankNumber;

        /**
         * @ORM\Column(type="decimal", precision=10, scale=4)
         * @Anon\LatLng()
         */
        public $lat;

        /**
         * @ORM\Column(type="decimal", precision=10, scale=4)
         * @Anon\LatLng()
         */
        public $lng;

        /**
         * @ORM\Column(type="string")
         * @Anon\ZipCodeNL()
         */
        public $zipcode;
        
        ...
    }
    
Op entity niveau kun je aangeven hoe/wanneer er geanonimiseerd moet worden. De allereerste keer dat het anonimiseercommando wordt gedraaid, altijd, of na een bepaalde datum

    /**
     * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
     * @ORM\Table(name="test")
     * @Anonymize(mode=Anonymize::AFTER_DATE, dateField="createdAt", dateInterval="P8M")
     */
    class Test implements AnonymizableInterface
    
Altijd is het makkelijkste en de standaard:

    /**
     * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
     * @ORM\Table(name="test")
     * @Anonymize()
     */
    class Test implements AnonymizableInterface
    
Maar bij een strategie (de manier waarop de anonimisering plaats vindt) waarbij de data bijvoorbeeld korter of langer 
wordt gemaakt, gebeurt dit dan elke keer. Bijvoorbeeld een hash strategie, zal de 2e keer de hash hashen

 Voor de overige 2 modes (De eerste keer en na een bepaalde datum) is het nodig dat de enity een interface implementeert. 
 Via de interface kan een datumveld worden opgehaald waarop de data is geanonimiseerd. Het makkelijkste is om hiervoor 
 de Anonymizable trait te gebruiken. Die voegt het veld ook gelijk toe.  
 
 De code kijkt dan naar die datum om te zien of de data al geanonimiseerd is. Bij first time wordt de anonimisering 
 uitgevoerd als die datum NULL is.  
 
     /**
      * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
      * @ORM\Table(name="test")
      * @Anonymize(mode=Anonymize::FIRST_TIME)
      */
     class Test implements AnonymizableInterface
     
Meest waarschijnlijke gebruik is echter dat je de data na een bepaalde periode wil anonimiseren

    /**
     * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
     * @ORM\Table(name="test")
     * @Anonymize(mode=Anonymize::AFTER_DATE, dateField="createdAt", dateInterval="P8M")
     */
    class Test implements AnonymizableInterface

Je moet dan naar een databaseveld verwijzen met een datetime, bijv. de createdAt of updatedAt. Als de datum waarop 
het console command wordt uitgevoerd meer dan __dateInterval__ nieuwer is dan de datum van die kolom, dan wordt de data
eenmalig worden geanonimiseerd.

Er zijn verschillende strategieen die gebruikt kunnen worden voor een property

    /**
     * @ORM\Column(type="string")
     * @Anon\Mask(char="#", maskCount=8, replaceCount=4)
     */
    public $bankNumber;

De waarde van deze property wordt gemaskeerd met __char__ volgens de instellingen. Het idee is om uiteindelijk meerdere
strategieen te maken, zoals LatLng, Zipcode, Regex, Hash etc.
