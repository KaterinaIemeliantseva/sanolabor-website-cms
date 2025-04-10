<?php
class ProgramKategorijaBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'program_kategorija';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'program_kategorija';
    public $audit_message_single = 'program_kategorija';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/
    
    function getList($data = [])
    {
        $data['array_search'] = ['program_kategorija.naziv'];

        
        $data['query'] = "SELECT program_kategorija.id, program_kategorija.naziv, program_kategorija.parent,
                program_kategorija.status, program_kategorija.sort, program_kategorija.nicename
                , if(parent.naziv is not null, parent.naziv, '-') as '_parent'
                FROM ". $this->table ."
            left join program_kategorija as parent on parent.id = program_kategorija.parent
            ";

        
        if(!empty($data['parent0']))
        {
            $sql .= " and program_kategorija.parent = 0 ";
        }

        $data['groupBy'] = ' program_kategorija.id ';
        $data['orderby'] = ' order by parent, sort asc ';


        return parent::getList($this->table,$data);
    }
}  
