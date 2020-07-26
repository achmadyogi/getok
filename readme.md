# GETOK - Geodetic Computation Tools

GETOK is a web-based application to compile geodetic computations built by Bandung Institute of Technology and PT Pertamina Indonesia. This application includes terrestrial and extra-terrestrial observations developed to fulfill the needs of either academic research or applied projects. Currently, This app has five engines that are in the middle of development process. This documentation is aimed to provide a solid explanation about the whole features of the app as the guide for developers to continue working with the project.

### Coordinate Conversion
Maps are always related to reference systems that vary across many mapping products. Field-engineering process may have to deal with many maps that use different coordinate systems. This app allows them to work with any coordinate system they want because they can easily convert it across available coordinate system. The app has four coordinate systems: geodetic, geocentric, UTM, and Mercator projection.

### Datum Transformation
Sometimes Pertamina encounters maps with different geodetic datums. Before doing any coordinate conversion, they have to make sure that the datum of two maps is well-adjusted. This app gives the flexibility to manipulate datums according to their observation location and time. Hence, engineers could make an appropriate decision before making any computation.

### GNSS Data Processing
This app calculates final coordinate values from direct satellite observations. The task mainly is to process rinnex data downloaded from the data center and produce coordinate values.

### Geoid Calculator
This application has geoid data in terms of grids that have been calculated in research activity. The app must give values at any point requested by users. Hence, the app simply does the interpolation process to create new values around available data. Geoid data used in this app cover Indonesian territory either land or water area.

### Adjustment Computation
In survey engineering, field-observation data must come across several computations and adjustments. This app provides an easier mechanism to calculate survey data. We compile the whole procedures into a single application so that engineers do only input the data and environment settings required. Then, the app gives the results exactly as needed.

If you would like to dive through the detail documentation, please refer to our wiki <a href="https://github.com/achmadyogi/getok/wiki">https://github.com/achmadyogi/getok/wiki</a>. Thank you!

Cheers,
Developer Team
