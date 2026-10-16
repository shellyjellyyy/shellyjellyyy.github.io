# Tutorial Content Templates

This folder contains the full text content for each tutorial. Copy these into the WordPress editor when creating tutorials in the admin dashboard.

## How to create tutorials

1. Go to **Tutorials → Add New** in the WordPress dashboard
2. Set the **title** exactly as shown
3. Paste the content below into the **Block Editor** or **Classic Editor**
4. Set the **Tutorial Category** in the right sidebar
5. Set **Difficulty** to "Beginner"
6. Fill in the **Tutorial Details** meta box (time, software, Blender version, prerequisites, next tutorial URL)
7. Set **Featured Image** if you have one
8. Set the **Slug** (permalink) exactly as documented
9. Publish

---

## Tutorial 1: Low-Poly Barrel

**Title:** Low-Poly Barrel in Blender: A Step-by-Step Tutorial  
**Slug:** `low-poly-barrel-blender`  
**Category:** Low-Poly Modeling  
**Difficulty:** Beginner  
**Time:** 45 minutes  
**Software:** Blender 4.x  
**Blender Version:** 4.2 LTS  
**Prerequisites:** Basic familiarity with Blender's interface - you should know how to open a file, navigate the viewport with middle mouse, and access Edit Mode with Tab.  
**Next Tutorial URL:** /tutorials/uv-texturing/blender-uv-unwrapping-game-asset/  

**Excerpt:**  
Model a game-ready barrel from scratch using Blender's basic tools. This tutorial covers cylinder setup, loop cuts, bevel edges, and the geometry decisions that matter when the prop runs in a real-time game engine.

---

**FULL CONTENT (paste into editor):**

## What you will make

By the end of this tutorial, you will have a low-poly barrel mesh ready for UV unwrapping and texturing. It will have clean geometry, appropriate edge loops to hold its shape, and a polygon count low enough to work well as a game prop.

The barrel in this tutorial has roughly 200–300 faces. That is a reasonable starting point for an environmental prop in a low-poly game. Once you understand the construction, you can apply the same approach to crates, oil drums, water towers, and similar props.

## Before you start

You need Blender installed. This tutorial was written and tested against Blender 4.2 LTS, which is available free from [blender.org](https://www.blender.org/download/). The steps will work in any recent 4.x release.

You should be comfortable with:
- Navigating the viewport using middle-mouse drag (orbit), scroll wheel (zoom), and Shift + middle-mouse (pan)
- Switching between Object Mode and Edit Mode using Tab
- Selecting mesh elements with left-click

You do not need to know UV unwrapping or materials. Those come in the next tutorial.

## Why low-poly?

"Low-poly" refers to meshes that use a relatively small number of polygons. In real-time game engines, every polygon has a cost: the GPU must process each one every frame. Props that appear dozens of times in a scene - barrels, crates, rocks - need to be efficient.

A barrel silhouette can be read clearly with 12–16 sides on its cylinder. Adding more sides does not make the barrel look noticeably rounder in a low-poly art style, but it does increase the vertex count and make UV unwrapping harder. This tutorial uses 12 sides.

## Step 1 - Delete the default cube and add a cylinder

Open Blender. The default scene contains a cube, a light, and a camera. Press A to select everything, then X and Enter to delete.

Press Shift + A to open the Add menu. Go to Mesh → Cylinder.

At the bottom left of the viewport, you will see an **Add Cylinder** panel. Open it and set:
- **Vertices:** 12
- **Radius:** 0.4
- **Depth:** 0.8
- **Cap Fill Type:** N-Gon

Press Tab to enter Edit Mode. You should see a cylindrical mesh with 12 sides and two flat caps.

**Why 12 sides?** At game distances, 12 sides reads as a rounded barrel without looking jagged. More sides means more geometry to unwrap later. Fewer sides starts to look noticeably polygonal. 12 is a practical default for this type of prop.

## Step 2 - Scale the barrel to a realistic size

In Object Mode (press Tab to return), press S, then Z, then 1.5 and Enter. This stretches the barrel along the Z axis, making it taller than wide - closer to the proportions of a real wooden barrel.

Check the Dimensions panel (N key to open the right sidebar, then the Item tab). Your barrel should be approximately 0.8 m wide and 1.2 m tall. These are real-world Blender units, and they matter: Blender's default unit is the metre, so keeping proportions realistic means less scale correction when you export to Unity later.

## Step 3 - Add loop cuts for the barrel's middle bulge

Barrels are wider in the middle. To create this shape, you need horizontal edge loops.

Press Tab to enter Edit Mode. Press Ctrl + R to activate the Loop Cut tool. Move your mouse over the side of the cylinder - a yellow horizontal line should appear around the cylinder's circumference. Left-click to confirm the loop, then right-click or press Escape to place it exactly at the midpoint without sliding. This creates an edge loop around the middle of the barrel.

Add two more loop cuts: one about one-quarter of the way from the top, and one about one-quarter from the bottom. You now have three horizontal loops dividing the barrel's sides.

Select the middle loop (Alt + click to select an entire loop). Press S, then X (or Y), then 1.2 and Enter to scale it outward slightly. This creates the barrel's characteristic bulge. Adjust the amount until it looks natural - 1.15 to 1.25 is usually about right.

## Step 4 - Inset the top and bottom caps

The barrel's caps need a rim - a flat band at the top and bottom edge. This is also where you add the edge loops that prevent the top from looking flat.

In Edit Mode, select the top face (the N-Gon). Press I to inset. Move the mouse inward slightly and left-click to confirm an inset of about 0.05 units. This creates a thin ring of faces around the top edge and a smaller face inside.

Repeat for the bottom cap.

**Why inset?** Without this, the top and bottom edges will look too sharp when the model is shaded smooth. The inset gives you a bevel-like transition that reads well at game distances.

## Step 5 - Add bevel to the top and bottom edges

Select the edge loop that runs around the top rim (Alt + click). Hold Shift and also select the bottom rim edge loop.

Press Ctrl + B to activate Bevel. Move the mouse to create a small bevel - about 0.02 to 0.04 units. Scroll the mouse wheel to add one segment. Press Enter to confirm.

This softens the hard edge at the barrel's shoulders without adding a lot of geometry to the sides. In a low-poly game, visible hard edges on rounded shapes tend to draw the eye in the wrong way.

## Step 6 - Add the barrel bands (hoops)

Traditional wooden barrels have metal bands around them. You can suggest these with additional edge loops rather than separate geometry.

Add two more horizontal loops near the top third and bottom third of the barrel. Select these loops and scale them outward by 1.02 - just enough to create a slight bulge that reads as a metal band from a distance.

This is an example of a modeling shortcut: instead of modeling the actual metal band as a separate object, you suggest its presence by manipulating the existing geometry. For a low-poly game prop, this is usually sufficient.

## Step 7 - Check your polygon count

Press M to view the mesh statistics. With the barrel selected in Object Mode, look at the status bar at the bottom of the Blender window or enable overlays → Statistics in Edit Mode.

The barrel should have around 200–360 faces. If it has significantly more, check whether you added extra loop cuts accidentally or used too many bevel segments.

A rough guide for barrel-type props:
- **< 200 faces** - very low-poly; silhouette may suffer
- **200–400 faces** - appropriate for many low-poly games
- **400–800 faces** - acceptable for hero props or close-camera environments
- **> 800 faces** - unusually high for a barrel; consider reducing

## Step 8 - Apply smooth shading

In Object Mode, right-click the barrel and select **Shade Smooth**. Then open the Object Data Properties (the green triangle icon in the Properties panel) and find Auto Smooth. Enable it and set the angle to 30°.

This tells Blender to shade faces that meet at angles below 30° smoothly, and to leave sharper angles hard. The result is a barrel that looks rounded on the sides but maintains clean, visible edges at the rims and bands.

**What went wrong?** If the top and bottom caps look dark or shaded incorrectly, check whether the face normals are all pointing outward. In Edit Mode with overlays enabled, you can display normals (Viewport Overlay → Normals). If any face normals point inward (shown as a short line pointing into the mesh), select those faces and press Alt + N → Flip.

## Step 9 - Common mistakes and how to fix them

**The barrel looks blocky at the middle.**  
You scaled the loop cut too aggressively, or placed it at the wrong height. Select the middle loop and try a smaller scale value (1.1 instead of 1.2).

**The top and bottom faces shade incorrectly.**  
Check for internal faces or overlapping vertices. In Edit Mode, press M → Merge by Distance to clean up doubles, then recalculate normals with Shift + N.

**The bevel on the rim added too many segments.**  
Undo with Ctrl + Z, redo the bevel, and use the scroll wheel to set segments to 1. One segment is usually enough for a low-poly asset.

**The barrel is floating above the ground in Unity later.**  
This usually means the object's origin is not at the base. In Object Mode, right-click the barrel and choose **Set Origin → Origin to Geometry**, or select **Object → Set Origin → Origin to Geometry** from the header menu. To place the origin at the base specifically, move the 3D cursor to the barrel's base (Shift + right-click), then use **Object → Set Origin → Origin to 3D Cursor**.

## Step 10 - How to know the result is correct

Your barrel should:
- Have 12 sides visible when viewed from the top
- Be wider in the middle than at the top and bottom
- Have visible rim details at the top and bottom caps
- Show clean, uninterrupted loops when viewed in Wireframe mode (Z key)
- Have approximately 200–360 faces
- Shade smoothly across the sides with hard edges at the rims

If all of these are true, the geometry is ready for UV unwrapping.

## What comes next

The barrel you just built needs UV coordinates before you can apply textures to it properly. UV unwrapping maps the 3D surface of the barrel onto a flat 2D space, which is what texture images use.

The next tutorial in this series - [UV Unwrapping a Game Asset in Blender](/tutorials/uv-texturing/blender-uv-unwrapping-game-asset/) - continues with this same barrel mesh and explains seams, UV islands, and how to pack the layout efficiently.

## External references

- [Blender Manual: Loop Cut and Slide](https://docs.blender.org/manual/en/latest/modeling/meshes/editing/edge/loopcut_slide.html)
- [Blender Manual: Bevel](https://docs.blender.org/manual/en/latest/modeling/meshes/editing/edge/bevel.html)
- [Blender Manual: Face Normals](https://docs.blender.org/manual/en/latest/modeling/meshes/editing/mesh/normals.html)
