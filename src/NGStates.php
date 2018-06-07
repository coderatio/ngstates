<?php
/**
 * @library : NGStates
 * @author : Josiah O. Yahaya (Coderatio)
 * @license : MIT
 * @copyright : coderatio
 */

namespace Coderatio\NGStates;

class NGStates
{
    protected $message;

    /**
     * Initiate database
     *
     * @return bool|mixed|string
     */
    private function decodeLocations()
    {
        try {
            $locations = file_get_contents(storagePath('locations.json'));
            $locations = json_decode($locations);
            $this->message = true;

            return $locations;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }

        return false;

    }

    /**
     * Get all locations from DB
     *
     * @return array
     */
    protected function getLocations()
    {
        $locations = [];
        if ($this->decodeLocations()) {
            foreach($this->decodeLocations() as $location) {
                $locations[] = $location;

            }
        }

        return $locations;
    }

    /**
     * Get all states from database.
     *
     * @return array
     */
    public function getStates()
    {
        $states = [];
        if ($this->getLocations()) {
            foreach ($this->getLocations() as $location) {
                foreach ($location as $state) {
                    $states[] = $state;
                }
            }
        }

        return $states;
    }

    /**
     * Get a state by Name or ID
     *
     * @param mixed $stateNameOrId
     * @return mixed|null
     */
    public function getState($stateNameOrId)
    {
        if ($this->getStates()) {
            foreach ($this->getStates() as $state) {
                if (isset($state->id) && $state->id == $stateNameOrId || $state->name == $stateNameOrId) {
                    return $state;
                }
            }
        }

        return null;
    }

    /**
     * Get state by name
     *
     * @param string $stateName
     * @return mixed|null
     */
    public function getStateByName($stateName)
    {
        if ($this->getStates()) {
            foreach ($this->getStates() as $state) {
                if ($state->name == $stateName) {
                    return $state;
                }
            }
        }

        return null;
    }

    /**
    * Get state by ID
    * 
    * @param integer $state Is
    * @return object|null
    */
    public function getStateById($stateId)
    {
        if ($this->getStates()) {
            foreach ($this->getStates() as $state) {
                if ($state->id == $stateId) {
                    return $state;
                }
            }
        }

        return null;
    }

    /**
     * Get all state local governments
     *
     * @param mixed $stateNameOrId
     * @return null|array
     */
    public function getStateLocals($stateNameOrId)
    {
        if ($this->getStates()) {
            foreach ($this->getStates() as $state) {
                if ($state->id == $stateNameOrId || $state->name == $stateNameOrId) {
                    return $state->locals;
                }
            }
        }

        return null;
    }

    /**
     * Get state local government by name or id
     *
     * @param mixed $stateNameOrId
     * @param mixed $localNameOrId
     * @return null|object
     */
    public function getStateLocal($stateNameOrId, $localNameOrId)
    {
        $state = $this->getState($stateNameOrId);

        if (!is_null($state)) {
            foreach ($state->locals as $local) {
                if (isset($local->id) && $localNameOrId == $local->id || isset($local->name) && $localNameOrId == $local->name) {
                    return $local;
                }
            }
        }

        return null;
    }

    /**
     * Update a given state records
     *
     * @param mixed $stateNameOrId
     * @param array $newData
     * @return mixed|null
     */
    public function updateState($stateNameOrId, $newData)
    {
        if (!is_array($newData)) {
            return "New data must be an array.";
        }

        if (!is_null($this->getState($stateNameOrId))) {
            $state = $this->getState($stateNameOrId);
            $state->name = isset($newData['name']) ? $newData['name'] : $state->name;

            if (isset($newData['locals'])) {
                foreach ($newData['locals'] as $data) {
                    foreach ($state->locals as $local) {
                        if (isset($data['id']) && $local->id == $data['id'] && isset($data['name'])) {
                            $local->name = $data['name'];
                        }
                    }
                }
            }

            $state = json_encode($state);
            $newState = json_decode($state);

            $this->updateLocationsState($newState);

            return $this->getState($stateNameOrId);

        }

        return null;
    }

    /**
     * Update state local governments. Only found IDs will be updated.
     *
     * @param mixed $stateNameOrId
     * @param array $localsData
     * @return mixed|null|string
     */
    public function updateStateLocals($stateNameOrId, $localsData)
    {
        $state = $this->getState($stateNameOrId);

        if ($state) {
            if (is_array($localsData)) {
                foreach ($localsData as $data) {
                    foreach ($state->locals as $local) {
                        if (isset($data['id']) && $local->id == $data['id'] && isset($data['name'])) {
                            $local->name = $data['name'];
                        }
                    }
                }
            } else {

               return "Local government data must be array and multi-dimensional.";
            }

        }

        $this->updateLocationsState($state);

        return $this->getState($stateNameOrId);
    }

    /**
     * Add single state to database. This must be an array
     *
     * @param array $state
     * @return array|bool|mixed|null|string
     */
    public function addState($state)
    {
        $locations = $this->getLocations();

        if (!is_array($state)) {
            return "State data must be array.";
        }

        if (isset($state['id'])) {
            if (!is_null($this->getState($state['id']))) {
                $this->message = "State with the ID already exist.";

                return $this->getState($state['id']);
            }
        }

        if (!is_null($locations)) {
            array_push($locations, ['state' => $state]);

            if ($this->recreateLocations($locations)) {
                return $this->getLocations();
            }
        }

        return false;
    }

    /**
     * Add multiple states to database. Each must be an array keyed to 'state'.
     *
     * @param array $states
     * @return array|bool|string
     */
    public function addStates($states)
    {
        $locations = $this->getLocations();
        if (!is_array($states)) {
            return "States data must be array and mutli-dimensional.";
        }
        if (!is_null($locations)) {
            foreach ($states as $state) {
                if (isset($state['state']['id'])) {
                    if (is_null($this->getState($state['state']['id']))) {
                        array_push($locations, $state);
                    }
                }
            }

            if ($this->recreateLocations($locations))  {
                return $this->getLocations();
            }
        }

        return false;
    }

    /**
     * Add single local government for a state
     *
     * @param mixed $stateNameOrId
     * @param array $localData
     * @return mixed|null|string
     */
    public function addStateLocal($stateNameOrId, $localData)
    {
        if (!is_array($localData)) {
            return "Local government data must be array.";
        }

        $state = $this->getState($stateNameOrId);

        if ($state) {
            $local = $this->getStateLocal($state->id, $localData['id']);
            if ($local) {
                return "Local government already exist for the state.";
            }

            array_push($state->locals, $localData);
            $locals = json_encode($state->locals);
            $state->locals = json_decode($locals);

            $this->updateLocationsState($state);

            return $this->getState($stateNameOrId);
        }

        return "State not found";
    }

    /**
     * Add multiple local government for a state
     *
     * @param mixed $stateNameOrId
     * @param array $localsData
     * @return mixed|null|string
     */
    public function addStateLocals($stateNameOrId, $localsData)
    {
        if (!is_array($localsData)) {
            return "State local governments must be array.";
        }
        $state = $this->getState($stateNameOrId);
        if ($state) {
            $locals = $state->locals;
            foreach ($localsData as $data) {
                $local = $this->getStateLocal($state->id, $data['id']);
                if (!$local) {
                    array_push($locals, $data);
                }
            }
            $locals = json_encode($locals);
            $newLocals = json_decode($locals);
            $state->locals = $newLocals;

            $this->updateLocationsState($state);

            return $this->getState($stateNameOrId);
        }

        return "No state found with that ID.";
    }

    /**
     * Update a state local government
     *
     * @param mixed $stateNameOrId
     * @param array $localData
     * @return mixed|null|string
     */
    public function updateStateLocal($stateNameOrId, $localData)
    {
        $state = $this->getState($stateNameOrId);

        if (!is_array($localData)) {
            return "Local government data must be array";
        }

        if (!is_null($state)) {
            foreach ($state->locals as $local) {
                if (isset($localData['id']) && $localData['id'] == $local->id) {
                    $local->name = isset($localData['name']) ? $localData['name'] : $local->name;
                }

            }

            $this->updateLocationsState($state);

            return $this->getState($stateNameOrId);
        }

        return $state;
    }

    /**
     * Delete a state from database
     *
     * @param mixed $stateNameOrId
     * @return $this|array|bool
     */
    public function deleteState($stateNameOrId)
    {
        $singleState = $this->getState($stateNameOrId);
        $newLocations = [];

        if (!is_null($singleState)) {
            foreach ($this->getLocations() as $location) {
                foreach ($location as $state) {
                    if (isset($singleState->name) && $singleState->name != $state->name || isset($singleState->id) && $singleState->id != $state->id) {
                        array_push($newLocations, $location);
                    }
                }
            }

            if ($this->recreateLocations($newLocations)) {
                return $this->getLocations();
            }

            $this->message = "Failed to delete state.";

            return false;
        }

        $this->message = "No state with that ID found.";

        return $this;
    }

    /**
     * Delete a state local government
     *
     * @param mixed $stateNameOrId
     * @param integer $localId
     * @return $this|mixed|null
     */
    public function deleteStateLocal($stateNameOrId, $localId)
    {
        $state = $this->getState($stateNameOrId);
        $locals = [];

        if (!is_null($state)) {

            if (is_null($this->getStateLocal($state->id, $localId))) {
                $this->message = 'Local government no more exist.';
                return $this;
            }

            foreach ($state->locals as $local) {
                if ($local->id != $localId) {
                    array_push($locals, $local);
                }
            }

            $state->locals = $locals;

            $this->updateLocationsState($state);
            $this->message = "Local government deleted.";

            return $this->getState($stateNameOrId);
        }

        $this->message = 'No state with that ID found.';

        return $this;
    }

    /**
     * Update database state
     *
     * @param object $stateData
     * @return bool|mixed|null
     */
    protected function updateLocationsState($stateData)
    {
        $locations = $this->getLocations();

        foreach ($locations as $location) {
            foreach ($location as $state) {
                if ($state->name == $stateData->name || $state->id == $stateData->id) {
                    $state->name = $stateData->name;
                    $state->id = $stateData->id;
                    $state->locals = $stateData->locals;
                }
            }
        }

        if ($this->recreateLocations($locations)) {
            return $this->getState($stateData->id);
        }

        return false;
    }

    /**
     * Refresh NGStates database
     *
     * @param array $contents
     * @return array|bool
     */
    protected function recreateLocations($contents)
    {
        $contents = json_encode($contents);
        $put = file_put_contents(storagePath('locations.json'), $contents);

        if ($put) {
            return $this->getLocations();
        }

        return false;
    }
}
