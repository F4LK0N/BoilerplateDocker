<? defined("FKN") or http_response_code(403).die('Forbidden!');

use Phalcon\Http\Request;
use Phalcon\Filter\Filter;
use Phalcon\Filter\Validation;

class InputsProvider
{
    private int          $code    = eERROR::NONE;
    private string       $details = '';
    
    private string $method = '';
    private array  $fields = [];
    private array  $data   = [];



    private function setError(int $code=null, string $details='')
    {
        $this->code = $code??eERROR::INPUT;
        $this->details .= ($this->details?'\n':'').$details;
    }
    public function hasErrors(): bool
    {
        return ($this->code !== eERROR::NONE);
    }
    public function getErrorCode(): int
    {
        return $this->code;
    }
    public function getErrorDetails(): string
    {
        return $this->details;
    }



    public function get($name): mixed
    {
        return $this->data[$name];
    }



    public function values(array $fields): InputsProvider
    {
        $this->method = 'VALUES';
        if(!$this->setFields($fields)){
            return $this;
        }
        if(!$this->valuesRetrieve()){
            return $this;
        }
        if(!$this->filter()){
            return $this;
        }
        if(!$this->validate()){
            return $this;
        }
        return $this;
    }

    private function valuesRetrieve (): bool
    {
        try
        {
            $this->data = [];

            foreach($this->fields as $name => &$config)
            {
                if($config['value']===null){
                    if($config['validations']['required']===true){
                        $this->setError(eERROR::INPUT_RETRIEVE, "'$name' is required!");
                    }elseif(isset($config['default'])){
                        $config['value'] = $config['default'];
                    }else{
                        $config['value'] = '';
                    }
                }
                unset($config['default']);
                $this->data[$name] = &$config['value'];
            }
            
            return !$this->hasErrors();
        }
        catch (\Exception $exception) {
            VD($exception->getCode());
            $this->setError(eERROR::INPUT_RETRIEVE + $exception->getCode(), $exception->getMessage());
            return false;
        }
    }

    public function post(array $fields): InputsProvider
    {
        $this->method = 'POST';
        if(!$this->setFields($fields)){
            return $this;
        }
        if(!$this->postRetrieve()){
            return $this;
        }
        if(!$this->filter()){
            return $this;
        }
        if(!$this->validate()){
            return $this;
        }
        return $this;
    }

    private function postRetrieve (): bool
    {
        try
        {
            $request = new Request();

            if(!$request->isPost()){
                $this->setError(eERROR::INPUT_METHOD, 'Incorrect http method, POST expected!');
                return false;
            }

            foreach($this->fields as $name => &$config)
            {
                $config['value'] = $request->getPost($name, null, null);
                if($config['value']===null){
                    if($config['validations']['required']===true){
                        $this->setError(eERROR::INPUT_RETRIEVE, "'$name' is required!");
                    }elseif(isset($config['default'])){
                        $config['value'] = $config['default'];
                    }else{
                        $config['value'] = '';
                    }
                }
                unset($config['default']);
                $_POST[$name] = &$config['value'];
            }

            foreach($_POST as $name => &$value){
                if(!isset($this->fields[$name])){
                    $this->setError(eERROR::INPUT_RETRIEVE, "'$name' not allowed!");
                }
            }

            $this->data = &$_POST;
            
            return !$this->hasErrors();
        }
        catch (\Exception $exception) {
            VD($exception->getCode());
            $this->setError(eERROR::INPUT_RETRIEVE + $exception->getCode(), $exception->getMessage());
            return false;
        }
    }



    private function setFields(&$fields): bool
    {
        $this->fields = [];
        foreach($fields as $name => &$config)
        {
            //Name
            if(!is_string($name) || $name===''){
                $this->setError(eERROR::INPUT_CONFIG, 'Invalid field name!');
                continue;
            }

            //Type
            if(!isset($config['validations']['type'])){
                $config['validations']['type'] = 'string';
            }

            //Value
            if(!isset($config['value'])){
                $config['value'] = '';
            }

            //Filters
            if(!isset($config['filters']) || !is_array($config['filters'])){
                $config['filters'] = ['injection'];
            }
            elseif(!in_array('injection', $config['filters'])){
                array_unshift($config['filters'], 'injection');
            }

            //Validations
            if(!isset($config['validations']) || !is_array($config['validations'])){
                $config['validations'] = ['required'=>true];
            }
            elseif(!isset($config['validations']['required'])){
                $config['validations']['required'] = true;
            }else{
                $config['validations']['required'] = boolval($config['validations']['required']);
            }

            //Add
            $this->fields["$name"] = $config;
        }
        return !$this->hasErrors();
    }

    private function filter (): bool
    {
        try
        {
            /** @var Filter $filter */
            $filter = PROVIDER::GET('filter');
            foreach($this->fields as $name => &$config)
            {
                $config['value'] = $filter->sanitize($config['value'], $config['filters']);
                if($config['value']==='' && $config['validations']['required']===true){
                    $this->setError(eERROR::INPUT_FILTER, "'$name' is required!");
                }
            }
            return !$this->hasErrors();
        }
        catch (\Exception $exception) {
            $this->setError(eERROR::INPUT_FILTER + $exception->getCode(), $exception->getMessage());
            return false;
        }
    }

    private function validate (): bool
    {
        try
        {
            /** @var ValidatorProvider $validator */
            $validator = PROVIDER::GET('validator');

            foreach($this->fields as $name => &$config)
            {
               $validator->add($name, $config['validations']);
            }
            
            if(!$validator->validate($this->data)){
                $this->setError(eERROR::INPUT_VALIDATION, $validator->getErrorDetails());
            }

            return !$this->hasErrors();
        }
        catch (\Exception $exception) {
            $this->setError(eERROR::INPUT_VALIDATION + $exception->getCode(), $exception->getMessage());
            return false;
        }
    }

}



PROVIDER::SET(
    'inputs',
    function () {
        $inputs = new InputsProvider();
        return $inputs;
    }
);