
# Mars Rover Mission

Software that translates the commands sent from earth to instructions that are understood by the rover.

# Requirements
- Given the initial starting point (x,y) of a rover and the direction (N,S,E,W) it is facing.
- The rover receives a collection of commands. (E.g.) FFRRFFFRL
- The rover can move forward (f).
- The rover can move left/right (l,r).
- Planet is square.
- Obstacle detection before each move to a new square. If a given sequence of commands encounters an obstacle, the rover moves up to the last possible point, aborts the sequence and reports the obstacle

# How It Works
The application operates as follows:

1. __Input__: The user provides a series of commands ( movement instructions and direction for a rover).

2. __Execution__: The system processes each command:

3. __Movement Commands (F)__: The rover moves in the direction it is facing until stopped by an obstacle.

4. __Turning Commands (L and R)__: The rover changes its orientation by 90 degrees to the left or right, and move forward.

5. __Obstacle Detection__: If the rover encounters an obstacle, it stops and does not move further.

6. __Boundary Handling__: If the rover reaches the edge of the map, it "wraps around" to the opposite side (cyclic map behavior).

7. __Final Output__: After processing all commands, the system returns the commands send and executed, the original positicion and direction, the final position and direction, and the obstacle coordenates.

## Environment Variables

This project uses the following environment variables. You can set them in your `.env` file:

1. **MAP_WIDTH**  
   - Description: The width of the map (horizontal size in units).  
   - Default: `200`  
   - Example:
     ```plaintext
     MAP_WIDTH=200
     ```

2. **MAP_HEIGHT**  
   - Description: The height of the map (vertical size in units).  
   - Default: `200`  
   - Example:
     ```plaintext
     MAP_HEIGHT=100
     ```

3. **ROVER_OBSTACLES**  
   - Description: A comma-separated list of obstacle coordinates (x,y) that the rover cannot move through.  
   - Default: `[]`  
   - Example:
     ```plaintext
     ROVER_OBSTACLES="[[2, 0], [3, 0], [5, 5]]"
     ```

### Additional Notes:
- Ensure you set the correct values for the map's dimensions and the obstacle positions to match your application's logic.
- The `ROVER_OBSTACLES` variable should be provided as a string representation of an array of coordinates.


## Usage/Examples

You can interact with the rover's movement system by sending a `POST` request to the `/api/rover/move` endpoint. Below are examples of how to use this API.

### Request

- **Endpoint**: `/api/rover/move`
- **Method**: `POST`
- **Content-Type**: `application/json`

### Example of a request Body

```json
{
  "commands": "FFFFRRFFLFFR",
  "start_x": 199,
  "start_y": 1,
  "direction": "E"
}
```

### Example of a successful Response (with obstacle found):

```json
{
    "commands_send": "FFFFRRFFLFFR",
    "commands_executed": "FFFF",
    "from": {
        "x": 199,
        "y": 1,
        "direction": "E"
    },
    "to": {
        "x": 3,
        "y": 1,
        "direction": "E"
    },
    "obstacle": [
        3,
        0
    ]
}
```


### Samples explanation:

1. **Request**: Specifies the method (`POST`) and endpoint (`/api/rover/move`), as well as the content type (`application/json`).
2. **Request Body**: Includes the body of the request in JSON format with the parameters:
- `commands`: The series of commands to move the rover.
- `start_x`, `start_y`: The rover's starting position.
- `direction`: The rover's starting direction (in this example, `"E"` for East).
3. **Response**: Shows an example JSON response with:
- `commands_send`: The commands that were sent to the rover.
- `commands_executed`: The commands that the rover executed before encountering an obstacle.
- `from`: The rover's starting position and direction.
- `to`: The final position and direction of the rover after executing the commands.
- `obstacle`: The coordinates where an obstacle was encountered that stopped the rover.
   
## Installation

Follow these steps to set up the project locally:

### 1. Clone the Repository

First, clone the repository to your local machine:

```bash
git clone https://github.com/Xacbert/housfy.git
cd housfy
```

### 2. Install Dependencies

Run the following command to install the required dependencies via Composer:

```bash
composer install
```

### 3. Set Up the .env File
Copy the .env.example file to .env to configure your environment settings:

```bash
cp .env.example .env
```

You may need to adjust the settings in your .env file (e.g., map size and obstacles).

### 4. Generate the Application Key

The Laravel application requires an application key. Generate it by running the following command:

```bash
php artisan key:generate
```

This will set the APP_KEY in your .env file, which is required for encryption and other services.

### 5. Run the Application Locally

To run the application locally, use the built-in Laravel development server by running:

```bash
php artisan serve
```