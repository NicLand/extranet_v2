// Do a montage from a composite made by "auto_composite_VX".
// created by Nicolas LANDREIN nicolas.landrein@u-bordeaux.fr

// dialog window to determine the slice's number (important : not the wavelength number)
Dialog.create("Channels number");
Dialog.addNumber("Channel's number :",4);
Dialog.show();

nChannel = Dialog.getNumber();

// create a square selection
makeRectangle(100, 100, 300, 150);
waitForUser("Selection","Select the aera to make montage");

//duplicate the selected region and remove the slice containing the scale bar
run("Duplicate...", "duplicate");
rename("toStack.tif");
setSlice(1);
run("Delete Slice", "delete=channel");

//duplicate the selected aera without the scale bar
run("Duplicate...", "duplicate");
rename("a_flat_with_phase.tif");

//duplicate the selected aera to remove the phase contrast slice and flat the rest
run("Duplicate...", "duplicate");
rename("b_flat_without_phase.tif");
setSlice(nChannel-1);
run("Delete Slice", "delete=channel");
run("Flatten");

//flat the stack with all the slices
selectWindow("a_flat_with_phase.tif");
run("Flatten");

//prepare the montage
selectWindow("toStack.tif");
run("Split Channels");

col=nChannel+1;
run("Images to Stack", "name=montage title=[] use");
Stack.swap(2,5);
run("Make Montage...", "columns=col rows=1 scale=1 use");
rename("stackMounted");run("Scale Bar...", "width=5 height=2 font=10 color=White background=None location=[Lower Right] bold");





