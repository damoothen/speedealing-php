## jQuery Datatables Library for PHP

### Usage:
1. Register the library to your autoloader
2. Create a table definition / Schema
3. Fetch the data from database and inject to the schema class you've just created (compatible with PDO recordset)
4. Output the datatable class in your template / view file

### Todo:
- Support localization
- Handle for complex search query

##### Schema Definition Example
~~~
namespace app\schema;

use datatables\Schema;

class UserSchema extends Schema {
    
    /* ______________________________________________________________________ */
    
    public function init() {
        // variable to be used inside closure object
        $schema = $this;
        
        // checkbox
        $this->push('check', array(
            'type'         => 'static',
            'label'        => (string) $this->element('Checkbox', array('ca', 1, 'ca')), 
            'width'        => 8,
            'sortable'     => false,
            'searchable'   => false,
            'outputFilter' => function($input, $row) use($schema) {
                return (string) $schema->element('Checkbox', array('id[]', $row['id']));
            }
        ));
        
        // actions
        $this->push('action', array(
            'type'         => 'static',
            'width'        => 35,
            'sortable'     => false,
            'searchable'   => false,
            'outputFilter' => function($input, $row) use($schema) {
                return $schema->element('EditLink', array("edit/{$row['id']}")) . 
                    $schema->element('DeleteLink', array("delete/{$row['id']}"));
            }
        ));
        
        // username
        $this->push('username', array(
            'label'        => 'Username',
            'footer'       => $this->element('FilterInput'),
        ));
        
        // name
        $this->push('name', array(
            'label'        => 'Name',
            'footer'       => $this->element('FilterInput'),
            'outputFilter' => function($input, $row) {
                return $input ? $input : '&mdash;';
            }
        ));
        
        // email
        $this->push('email', array(
            'label'        => 'Email',
            'footer'       => $this->element('FilterInput'),
        ));
        
        // role
        $roleOptions = array(
            'admin' => 'Admin', 
            'user'  => 'User'
        );
        $this->push('role', array(
            'label'        => 'Role',
            'footer'       => $this->element('FilterSelect', array($roleOptions)),
            'outputFilter' => function($input, $row) use($roleOptions) {
                return $roleOptions[$input];
            }
        ));
        
        // register another fields
    }
}
~~~

##### In your Controller Action / Logic
In this example, I put html and json request in one place.
~~~
class UserController {
    ...
    public function indexAction() {
        $data_source = "http://www.example.com/user/index.json";
        $table = new datatables\Datatables(compact('data_source'));
        $table->setSchema(new UserSchema); // schema class you've just created'
        
        // If json request, fetch data from database and format the data
        if($this->request->is('json')) {
            
            $data = ... // Fetch data from database (must be an array or Objects that implements SPL's Iterator)
            $count = ... // Num. of total records
            
            $request = new datatables\Request($table->getSchema(), $this->request->sanitize($_GET));
            $output = $table->formatJsonOutput($table->getSchema()->adapt($data), $count);
            
            // Will just straight forward here
            header('Content-Type', 'application/json');
            echo $output;
            exit;
            
        } else {
            // Add some plugins
            $table->plug(new RowSelect);
            $table->plug(new DeleteNotification);
            
            // render view or just output the datatable
            return compact('table'); // echo $table;
        }
    }
    ...
}
~~~

Please refer to the jQuery datatables documentation
http://datatables.net/

![alt text](http://s11.postimage.org/44smk42ub/table.png "Screenshot")
